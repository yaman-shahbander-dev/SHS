<?php
/**
 * File name: CategoryDataTable.php
 * Last modified: 2020.04.30 at 08:21:08
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2020
 *
 */

namespace App\DataTables;

use App\Models\BannedUsers;
use App\Models\CustomField;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;
use Barryvdh\DomPDF\Facade as PDF;

class BannedUsersDataTable extends DataTable
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
            ->editColumn('user_id', function ($BannedUsers) { // User name(RelationShip)
                return getBannedUsername($BannedUsers);
            })
            ->editColumn('description', function ($BannedUsers) {
                    return $BannedUsers->description;
             })   
            ->editColumn('created_at', function ($BannedUsers) {
                return getDateColumn($BannedUsers, 'created_at');
            })
            ->editColumn('temporary_ban', function ($BannedUsers) {
                return getDateColumn($BannedUsers, 'temporary_ban');
             })
             ->editColumn('forever_ban', function ($BannedUsers) {
                 If ($BannedUsers->forever_ban == 0) { return "No"; }
                 else { return "Yes"; }
             })
            ->addColumn('action', 'bannedUsers.datatables_actions')
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
                'data' => 'user_id',
                'title' => trans('lang.user_name'),
            ],
            [
                'data' => 'description',
                'title' => trans('lang.description'),
                'searchable' => true,
            ],
            [
                'data' => 'created_at',
                'title' => trans('lang.created_at'),
                'searchable' => false,
            ],
            [
               'data' => 'temporary_ban',
               'title' => trans('lang.temporary_ban'),
               'searchable' => false,
            ],
            [
               'data' => 'forever_ban',
               'title' => trans('lang.forever_ban'),
               'searchable' => false,
           ]
        ];

        $hasCustomField = in_array(BannedUsers::class, setting('custom_field_models', []));
        if ($hasCustomField) {
            $customFieldsCollection = CustomField::where('custom_field_model', BannedUsers::class)->where('in_table', '=', true)->get();
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
    public function query(BannedUsers $model)
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
        return 'categoriesdatatable_' . time();
    }
}