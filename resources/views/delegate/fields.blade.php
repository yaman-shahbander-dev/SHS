@if($customFields)
    <h5 class="col-12 pb-4">{!! trans('lang.main_fields') !!}</h5>
@endif

<div style="flex: 50%;max-width:100%;padding: 0 4px;" class="column">
    <div class="row">
        <!-- Name Field -->
        <div class="form-group row col-md-6">
            {!! Form::label('name', trans("lang.delegate_name"), ['class' => 'col-4 control-label text-right']) !!}
            <div class="col-8">
                {!! Form::text('name', null,  ['class' => 'form-control','placeholder'=>  trans("lang.user_name_placeholder"),'required']) !!}
                <div class="form-text text-muted">
                    Name
                </div>
            </div>
        </div>
        <!-- username Field -->
        <div class="form-group row col-md-6">
            {!! Form::label('phone', trans('lang.phone'), ['class' => 'col-4 control-label text-right']) !!}
            <div class="col-8">
                {!! Form::text('phone', null,  ['class' => 'form-control','placeholder'=>  'phone','required']) !!}
                <div class="form-text text-muted">
                phone
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <!-- balance Field -->
        <div class="form-group row col-md-6">
            {!! Form::label('balance', trans('lang.balance'), ['class' => 'col-4 control-label text-right']) !!}
            <div class="col-8">
                {!! Form::number('balance', Request::is('*edit') ? $saleMan->Balance->balance : null,  ['class' => 'form-control','placeholder'=>  'Balance']) !!}
                <div class="form-text text-muted">
                default 0
                </div>
            </div>
        </div>
    </div>
</div>
 
@if($customFields)
    {{--TODO generate custom field--}}
    <div class="clearfix"></div>
    <div class="col-12 custom-field-container">
        <h5 class="col-12 pb-4">{!! trans('lang.custom_field_plural') !!}</h5>
        {!! $customFields !!}
    </div>
@endif
<!-- Submit Field -->
<div class="form-group col-12 text-right">
    <button type="submit" class="btn btn-{{setting('theme_color')}}"><i class="fa fa-save"></i> {{trans('lang.save')}} </button>
    <a href="{!! route('users.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i> {{trans('lang.cancel')}}</a>
</div>
