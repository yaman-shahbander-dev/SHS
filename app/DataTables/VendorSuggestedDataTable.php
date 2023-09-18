<?php

namespace App\DataTables;
use App\vendors_suggested;
use App\Models\CustomField;
use App\Models\User;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;
use Barryvdh\DomPDF\Facade as PDF;

class VendorSuggestedDataTable extends DataTable
{

    /**
     * @var array
     */
    public static $customFields = [];
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $dataTable = new EloquentDataTable($query);
        $columns = array_column($this->getColumns(), 'data');
        return $dataTable
            ->editColumn('created_at', function ($vendor) {
                return getDateColumn($vendor, 'created_at');
            })

            ->addColumn('action', 'settings.vendors_suggested.datatables_actions')
            ->rawColumns(array_merge($columns, ['action']));



    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\vendors_suggested $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(vendors_suggested $model)
    {
        // getting vendors from database
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->addAction(['title'=>trans('lang.actions'),'width' => '80px', 'printable' => false, 'responsivePriority' => '100'])
            ->parameters(array_merge(
                config('datatables-buttons.parameters'), [
                    'language' => json_decode(
                        file_get_contents(base_path('resources/lang/'.app()->getLocale().'/datatable.json')
                        ),true)
                ]
            ));
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        $columns = [

            [
                'data' => 'name',
                'title' => trans('lang.user_name'),
                'searchable' => true,
            ],
            [
                'data' => 'email',
                'title' => trans('lang.user_email'),
                'searchable' => true,
            ],
        
            // [
            //     'data' => 'subscription_id',
            //     'title' =>'Subscription',
            //     'searchable' => false, 'orderable' => false, 'exportable' => false, 'printable' => false,
            // ],
            [
                'data' => 'phone',
                'title' => trans('lang.phone'),
                'searchable' => true, 'orderable' => false, 'exportable' => false, 'printable' => false,
            ],
            [
                'data' => 'created_at',
                'title' => trans('lang.created_at'),
                'searchable' => false,
            ]
        ];

        $hasCustomField = in_array(vendors_suggested::class, setting('custom_field_models',[]));
        if ($hasCustomField) {
            $customFieldsCollection = CustomField::where('custom_field_model', vendors_suggested::class)->where('in_table', '=', true)->get();
            foreach ($customFieldsCollection as $key => $field) {
                array_splice($columns, $field->order - 1, 0, [[
                    'data' => 'custom_fields.' . $field->name . '.view',
                    'title' => trans('lang.user_' . $field->name),
                    'orderable' => false,
                    'searchable' => false,
                ]]);
            }
        }
        return $columns;
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'vendoresuggestedsdatatable_' . time();
    }

    /**
     * Export PDF using DOMPDF
     * @return mixed
     */
    public function pdf()
    {
        $data = $this->getDataForPrint();
        $pdf = PDF::loadView($this->printPreview, compact('data'));
        return $pdf->download($this->filename() . '.pdf');
    }
}
