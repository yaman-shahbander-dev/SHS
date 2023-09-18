<?php
/**
 * File name: ReviewDataTable.php
 * Last modified: 2020.04.30 at 08:21:08
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2020
 *
 */

namespace App\DataTables;


use App\Models\CustomField;
use App\Models\reviews;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;
use Barryvdh\DomPDF\Facade as PDF;

class ReviewsDataTable extends DataTable
{
    /**
     * custom fields columns
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
        $dataTable = $dataTable

            ->editColumn('created_at', function ($review) {
                return getDateColumn($review, 'created_at');
            })
            ->editColumn('full_rating',function ($review){
                return getFullRating($review);
            })
            ->editColumn('vendor_id',function ($review){
                return getVendorName($review);
            })
            ->editColumn('client_id',function ($review){
                return getClientName($review);
            })
            ->addColumn('action', 'reviews.datatables_actions')
            ->rawColumns(array_merge($columns, ['action']));

        return $dataTable;
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
                'data' => 'vendor_id',
                'title' => trans('lang.SP_name'),
                'searchable' => false,
            ],
            [
                'data' => 'client_id',
                'title' => trans('lang.reviewer'),
                'searchable' => false,
            ],
            [
                'data' => 'vendors.phone',
                'title' => trans('lang.phone'),
                'searchable' => true,
            ],
            [
                'data' => 'description',
                'title' => trans('lang.description'),
                'searchable' => true,
            ],
            [
                'data' => 'full_rating',
                'title' => trans('lang.rating'),
                'searchable' => false,
            ],
            [
                'data' => 'created_at',
                'title' => trans('lang.created_at'),
                'searchable' => false,
            ]
            
        ];

        $hasCustomField = in_array(reviews::class, setting('custom_field_models', []));
        if ($hasCustomField) {
            $customFieldsCollection = CustomField::where('custom_field_model', reviews::class)->where('in_table', '=', true)->get();
            foreach ($customFieldsCollection as $key => $field) {
                array_splice($columns, $field->order - 1, 0, [[
                    'data' => 'custom_fields.' . $field->name . '.view',
                    'title' => trans('lang.category_' . $field->name),
                    'orderable' => false,
                    'searchable' => false,
                ]]);
            }
        }
        return $columns;
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Post $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(reviews $model)
    {
        return $model->newQuery()->with('vendors')->where('approved',1);
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
                        file_get_contents(base_path('resources/lang/' . app()->getLocale() . '/datatable.json')
                        ), true)
                ]
            ));
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

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'reviewsdatatable' . time();
    }
}