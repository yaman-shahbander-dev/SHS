<?php
/**
 * File name: SuggestionsDataTable.php
 * Last modified: 2020.04.30 at 08:21:09
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2020
 *
 */

namespace App\DataTables;

use App\Models\Suggestion;
use App\Models\Restaurant;
use Barryvdh\DomPDF\Facade as PDF;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;

class SuggestionsDataTable extends DataTable
{

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

            ->editColumn('Message', function ($suggestion) {
                return getMsgColumn($suggestion, 'msg');
            })
            ->editColumn('Restaurant Manager', function ($suggestion) {
                return getUserColumn($suggestion, 'user_id');
            })
            ->editColumn('Restaurant ', function ($suggestion) {
                return getRestaurantColumn($suggestion, 'restaurant_id');
            })
            ->editColumn('updated_at', function ($suggestion) {
                return getDateColumn($suggestion, 'updated_at');
            })
            ->rawColumns(array_merge($columns))
            //->addColumn('action', 'suggestions.datatables_actions')
            ->rawColumns(array_merge($columns));

        return $dataTable;
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Post $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Suggestion $model)
    {
        if (auth()->user()->hasRole('admin')) {
            return $model->newQuery()->with("restaurant")->with("user")->select('suggestions.*');
        }

        /*if (auth()->user()->hasRole('admin')) {
            return $model->newQuery()->with("restaurant")->select('galleries.*');
        } else {
            return $model->newQuery()->with("restaurant")
                ->join("user_restaurants", "user_restaurants.restaurant_id", "=", "galleries.restaurant_id")
                ->where('user_restaurants.user_id', auth()->id())
                ->select('galleries.*');
        }*/


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
            ->parameters(array_merge(
                [
                    'language' => json_decode(
                        file_get_contents(base_path('resources/lang/' . app()->getLocale() . '/datatable.json')
                        ), true),
                    'order' => [ [0, 'desc'] ],
                ],
                config('datatables-buttons.parameters')
            ))
            ;
            ;
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
                'data' => 'user.name',
                'title' => 'Restaurant Manager',

            ],
            [
                'data' => 'restaurant.name',
                'title' => 'Restaurant',
                //'title' => trans('lang.restaurant_address'),

            ],
            [
                'data' => 'msg',
                'title' => 'Message',

            ],
            [
                'data' => 'updated_at',
                'title' => 'Sent at',
                'searchable' => false,
            ]
        ];


        return $columns;
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'suggestionsdatatable_' . time();
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