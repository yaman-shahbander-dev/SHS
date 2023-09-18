<?php
/**
 * File name: CategoryDataTable.php
 * Last modified: 2020.04.30 at 08:21:08
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2020
 *
 */

namespace App\DataTables;

use App\Models\specialOffers;
use App\Models\CustomField;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;
use Barryvdh\DomPDF\Facade as PDF;

class SpecialOffersDataTable extends DataTable
{
    /**
     * custom fields columns
     * @var array
     */

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
            ->editColumn('updated_at', function ($special) {
                return getDateColumn($special, 'updated_at');
            })
            ->editColumn('created_at', function ($special) {
                return getDateCreatedAtColumn($special, 'created_at');
            })
            ->editColumn('subcategory_id', function ($special) {
                return $special->subcategories->name;
            })
            ->editColumn('category', function ($special) {
                return $special->subcategories->categories->name;
            })
            ->editColumn('image', function ($special) {
                if ($special->image != null) {
                    return '<img src='. asset('storage/specialOffersPic') . '/' . $special->image.' style="width: 82px; height: 65px !important;">';
                } else {
                    return '<img width="80px" height="80px" src='. asset("storage/specialOffersPic/default.jpg") . '>';
                }
                
            })
            ->addColumn('action', 'special_offers.datatables_actions')
            ->editColumn('user_id', function ($special) {
                return getsSpecialRelationName($special);
            })
            ->rawColumns(array_merge($columns, ['action']));

        return $dataTable;
    }

    //asset('storage/specialOffersPic') . '/' .$imageName;

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        $columns = [
            [
                'data' => 'description',
                'title' => trans('lang.description'),
                'searchable' => true,
            ],
            [
                'data' => 'user_id',
                'title' => trans('lang.offer_owner'),
                'searchable' => false, 'orderable' => false, 'exportable' => false, 'printable' => false,
            ],
            [
                'data' => 'subcategory_id',
                'title' => trans('lang.subcategory'),
                'searchable' => false,
            ],
            [
                'data' => 'category',
                'title' => trans('lang.category'),
                'searchable' => false,
            ],
            [
                'data' => 'image',
                'title' => trans('lang.image'),
            ],
            [
                'data' => 'updated_at',
                'title' => trans('lang.user_updated_at'),
                'searchable' => false,
            ],
            [
                'data' => 'created_at',
                'title' => trans('lang.created_at'),
                'searchable' => false,
            ]

        ];

        $hasCustomField = in_array(specialOffers::class, setting('custom_field_models', []));
        if ($hasCustomField) {
            $customFieldsCollection = CustomField::where('custom_field_model', specialOffers::class)->where('in_table', '=', true)->get();
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
    public function query(specialOffers $model)
    {
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
        return 'special_offers_datatable_' . time();
    }
}