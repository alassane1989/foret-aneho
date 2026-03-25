<?php

namespace App\Exports;

use App\Models\Contact;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Http\Request;

class ContactsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithEvents
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $query = Contact::query();

        // Appliquer les mêmes filtres que dans l'index
        if ($this->request->has('statut') && $this->request->statut != '') {
            $estLu = $this->request->statut == 'lu';
            $query->where('lu', $estLu);
        }

        if ($this->request->has('sujet') && $this->request->sujet != '') {
            $query->where('sujet', $this->request->sujet);
        }

        if ($this->request->has('search') && $this->request->search != '') {
            $searchTerm = $this->request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('nom', 'like', '%' . $searchTerm . '%')
                  ->orWhere('email', 'like', '%' . $searchTerm . '%')
                  ->orWhere('message', 'like', '%' . $searchTerm . '%');
            });
        }

        // Tri
        if ($this->request->has('tri') && $this->request->tri == 'ancien') {
            $query->orderBy('created_at', 'asc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        return $query->get();
    }

    /**
    * @return array
    */
    public function headings(): array
    {
        return [
            'ID',
            'Nom',
            'Email',
            'Sujet',
            'Message',
            'Statut',
            'Date de traitement',
            'Réponse',
            'Date de réponse',
            'Date de création'
        ];
    }

    /**
    * @param mixed $contact
    * @return array
    */
    public function map($contact): array
    {
        return [
            $contact->id,
            $contact->nom,
            $contact->email,
            $contact->sujet_label,
            $contact->message,
            $contact->lu ? 'Lu' : 'Non lu',
            $contact->date_traitement ? $contact->date_traitement->format('d/m/Y H:i') : '',
            $contact->reponse ?? '',
            $contact->date_reponse ? $contact->date_reponse->format('d/m/Y H:i') : '',
            $contact->created_at->format('d/m/Y H:i')
        ];
    }

    /**
    * @param Worksheet $sheet
    */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }

    /**
    * @return array
    */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet;
                
                // Style pour l'en-tête
                $sheet->getStyle('A1:J1')->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '4A5568']
                    ],
                    'font' => [
                        'color' => ['rgb' => 'FFFFFF'],
                        'bold' => true
                    ]
                ]);

                // Encadrer toutes les cellules
                $lastRow = $sheet->getHighestRow();
                $sheet->getStyle('A1:J' . $lastRow)
                    ->getBorders()->getAllBorders()
                    ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                // Alignement du texte
                $sheet->getStyle('A1:J' . $lastRow)
                    ->getAlignment()
                    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

                // Largeur des colonnes
                $sheet->getColumnDimension('A')->setWidth(8);  // ID
                $sheet->getColumnDimension('B')->setWidth(20); // Nom
                $sheet->getColumnDimension('C')->setWidth(30); // Email
                $sheet->getColumnDimension('D')->setWidth(25); // Sujet
                $sheet->getColumnDimension('E')->setWidth(40); // Message
                $sheet->getColumnDimension('F')->setWidth(12); // Statut
                $sheet->getColumnDimension('G')->setWidth(18); // Date traitement
                $sheet->getColumnDimension('H')->setWidth(40); // Réponse
                $sheet->getColumnDimension('I')->setWidth(18); // Date réponse
                $sheet->getColumnDimension('J')->setWidth(18); // Date création
            },
        ];
    }
}