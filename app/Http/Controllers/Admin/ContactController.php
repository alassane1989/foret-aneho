<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;
use App\Models\User;
use App\Mail\ContactReply;
use App\Exports\ContactsExport;
use App\Exports\ContactsPdfExport;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class ContactController extends Controller
{
    /**
     * Vérification centralisée des permissions
     */
    private function authorizePermission(string $permission, string $redirectRoute = 'admin.dashboard')
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (!$user || !$user->aPermission($permission)) {
            return redirect()->route($redirectRoute)
                ->with('error', 'Action non autorisée.');
        }

        return null;
    }

    /**
     * Liste des messages
     */
    public function index(Request $request)
    {
        if ($response = $this->authorizePermission('contacts.voir')) {
            return $response;
        }

        $query = Contact::query();

        if ($request->filled('statut')) {
            $query->where('lu', $request->statut === 'lu');
        }

        if ($request->filled('sujet')) {
            $query->where('sujet', $request->sujet);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nom', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('message', 'like', '%' . $request->search . '%');
            });
        }

        $query->orderBy(
            'created_at',
            $request->tri === 'ancien' ? 'asc' : 'desc'
        );

        $messages = $query->paginate(20);

        // Statistiques complètes avec par_sujet
        $stats = [
            'total' => Contact::count(),
            'non_lus' => Contact::where('lu', false)->count(),
            'lus' => Contact::where('lu', true)->count(),
            'repondu' => Contact::whereNotNull('reponse')->count(),
            'par_sujet' => [
                'info' => Contact::where('sujet', 'info')->count(),
                'visite' => Contact::where('sujet', 'visite')->count(),
                'participation' => Contact::where('sujet', 'participation')->count(),
                'projet' => Contact::where('sujet', 'projet')->count(),
                'partenariat' => Contact::where('sujet', 'partenariat')->count(),
                'autre' => Contact::where('sujet', 'autre')->count(),
            ]
        ];

        return view('admin.contacts.index', compact('messages', 'stats'));
    }

    public function show($id)
    {
        if ($response = $this->authorizePermission('contacts.voir')) {
            return $response;
        }

        $message = Contact::findOrFail($id);

        if (!$message->lu) {
            $message->marquerCommeLu();
        }

        return view('admin.contacts.show', compact('message'));
    }

    public function markAsRead($id)
    {
        if ($response = $this->authorizePermission('contacts.modifier', 'admin.contacts.index')) {
            return $response;
        }

        $message = Contact::findOrFail($id);
        $message->marquerCommeLu();

        return back()->with('success', 'Message marqué comme lu.');
    }

    public function markAsUnread($id)
    {
        if ($response = $this->authorizePermission('contacts.modifier', 'admin.contacts.index')) {
            return $response;
        }

        $message = Contact::findOrFail($id);
        $message->update([
            'lu' => false,
            'date_traitement' => null
        ]);

        return back()->with('success', 'Message marqué comme non lu.');
    }

    public function reply($id)
    {
        if ($response = $this->authorizePermission('contacts.repondre', 'admin.contacts.index')) {
            return $response;
        }

        $message = Contact::findOrFail($id);

        return view('admin.contacts.reply', compact('message'));
    }

    public function sendReply(Request $request, $id)
    {
        if ($response = $this->authorizePermission('contacts.repondre', 'admin.contacts.index')) {
            return $response;
        }

        $message = Contact::findOrFail($id);

        $request->validate([
            'reponse' => 'required|string',
            'sujet_reponse' => 'required|string|max:255',
        ]);

        try {
            Mail::to($message->email)
                ->send(new ContactReply($message, $request->reponse, $request->sujet_reponse));

            $message->update([
                'reponse' => $request->reponse,
                'date_reponse' => now()
            ]);

            if (!$message->lu) {
                $message->marquerCommeLu();
            }

            return redirect()
                ->route('admin.contacts.show', $message->id)
                ->with('success', 'Réponse envoyée avec succès.');

        } catch (\Exception $e) {
            return back()
                ->with('error', 'Erreur lors de l\'envoi : ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        if ($response = $this->authorizePermission('contacts.supprimer', 'admin.contacts.index')) {
            return $response;
        }

        Contact::findOrFail($id)->delete();

        return redirect()
            ->route('admin.contacts.index')
            ->with('success', 'Message supprimé.');
    }

    public function massDestroy(Request $request)
    {
        if ($response = $this->authorizePermission('contacts.supprimer', 'admin.contacts.index')) {
            return $response;
        }

        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:contacts,id'
        ]);

        Contact::whereIn('id', $request->ids)->delete();

        return redirect()
            ->route('admin.contacts.index')
            ->with('success', count($request->ids) . ' message(s) supprimé(s).');
    }

    public function massMarkAsRead(Request $request)
    {
        if ($response = $this->authorizePermission('contacts.modifier', 'admin.contacts.index')) {
            return $response;
        }

        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:contacts,id'
        ]);

        Contact::whereIn('id', $request->ids)
            ->where('lu', false)
            ->update([
                'lu' => true,
                'date_traitement' => now()
            ]);

        return redirect()
            ->route('admin.contacts.index')
            ->with('success', count($request->ids) . ' message(s) marqué(s) comme lu(s).');
    }

    public function exportExcel(Request $request)
    {
        if ($response = $this->authorizePermission('contacts.exporter', 'admin.contacts.index')) {
            return $response;
        }

        return Excel::download(
            new ContactsExport($request),
            'contacts_' . now()->format('Y-m-d_His') . '.xlsx'
        );
    }

    public function exportPdf(Request $request)
    {
        if ($response = $this->authorizePermission('contacts.exporter', 'admin.contacts.index')) {
            return $response;
        }

        $pdfExport = new ContactsPdfExport($request);
        $pdf = $pdfExport->generate();

        return $pdf->download(
            'contacts_' . now()->format('Y-m-d_His') . '.pdf'
        );
    }
}