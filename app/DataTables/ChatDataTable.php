<?php
/**
 * File name: ChatDataTable.php
 * Last modified: 2020.11.02 at 13:49:08
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2020
 *
 */

namespace App\DataTables;

use App\Models\Chat;
use App\Models\User;
use App\Models\Restaurant;
use App\Models\Order;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;
use Barryvdh\DomPDF\Facade as PDF;

class ChatDataTable extends DataTable
{
    
    public function dataTable($query)
    {
        $dataTable = new EloquentDataTable($query);
        $columns = array_column($this->getColumns(), 'data');
        
        $dataTable = $dataTable
            ->editColumn('restaurant_id', function ($chat) {
                return getRestaurantColumn($chat, 'restaurant_id');
            })
            ->editColumn('user_id', function ($chat) {
                return getuser_idColumn($chat, 'user_id');
            })
            ->editColumn('order_id', function ($chat) {
                return getOrder_idColumn($chat, 'order_id');
            })
            ->editColumn('created_at', function ($chat) {
                return getDateColumn($chat, 'created_at');
            })
            //->addColumn('action', 'chats.datatables_actions')
            ->addColumn('action', function($chat){
                            
                            $url = url('chat/'.$chat->id);
                           $btn = '<a href='.$url.' class="edit btn btn-info btn-sm" target="_blank">View Chat</a>';
                          
         
                            return $btn;
                    })
                    ->rawColumns(['action'])
            
            ->rawColumns(array_merge($columns, ['action']));
            
//getuser_idColumn
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
                'data' => 'restaurant.name',
                'title' => 'Restaurant' //trans('lang.category_name'),

            ],
            [
                'data' => 'order.id',
                'title' => 'Order ID',
            ],
            [
                'data' => 'user.name',
                'title' => 'Customer',
            ],
            [
                'data' => 'created_at',
                'title' => 'Created at',
                'searchable' => false,
            ]
        ];

        
        return $columns;
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Post $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Chat $model)
    {
        //return $model->newQuery();
        return $model->newQuery()->with("restaurant")->with("order")->with("user")->select('chats.*');
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
        return 'chatsdatatable_' . time();
    }
}