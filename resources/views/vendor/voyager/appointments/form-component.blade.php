<!-- GET THE DISPLAY OPTIONS -->
@php
    $display_options = $row->details->display ?? NULL;
    if ($dataTypeContent->{$row->field.'_'.($edit ? 'edit' : 'add')}) {
        $dataTypeContent->{$row->field} = $dataTypeContent->{$row->field.'_'.($edit ? 'edit' : 'add')};
    }
@endphp

<div class="form-group @if($row->type == 'hidden') hidden @endif col-md-{{ $display_options->width ?? 6 }} {{ $errors->has($row->field) ? 'has-error' : '' }}" @if(isset($display_options->id)){{ "id=$display_options->id" }}@endif>
    {{ $row->slugify }}
    @switch($row->field)
        @case('kind_of_medical_therapy_treatment')
            <label class="control-label">
                <strong>Supplementary question if YES </strong> - this could affect your change. Therefore, what kind is this treatment?
            </label>
            @break
        @case('kind_of_drugs_and_for_what')
            <label class="control-label">
                <strong>Supplementary question if YES</strong> - What are these drugs or what do you have to take them for?
            </label>
            @break
        @case('kind_of_surgery_and_when')
            <label class="control-label">
                <strong>Supplementary question if YES</strong> - What kind of surgery / when is this?
            </label>
            @break
        @default
            <label class="control-label">{{ $row->display_name }}</label>
            @include('voyager::multilingual.input-hidden-bread-edit-add')
    @endswitch
    
        
    @php
        $options = $row->details;
    @endphp

    {{-- we dont wan to show this field, but we need to have the input hidden
        check the field that's been included for more details --}}
    @if (isset($row->details->view) )
        @include($row->details->view, ['currentUserRole' => $currentUserRole,'options' => $row->details,'row' => $row, 'dataType' => $dataType, 'dataTypeContent' => $dataTypeContent, 'content' => $dataTypeContent->{$row->field}, 'action' => ($edit ? 'edit' : 'add')])
    @elseif($row->type == 'relationship' && $row->field == 'appointment_belongsto_user_relationship_1')
        @include('vendor.voyager.formFields.select_dropdown_createdby', ['options' => $row->details])
    @elseif ($row->type == 'relationship')
        @include('voyager::formfields.relationship', ['options' => $row->details])
    @else
        {!! app('voyager')->formField($row, $dataType, $dataTypeContent, $options , $currentUserRole) !!}
    @endif

    @foreach (app('voyager')->afterFormFields($row, $dataType, $dataTypeContent) as $after)
        {!! $after->handle($row, $dataType, $dataTypeContent) !!}
    @endforeach
    @if ($errors->has($row->field))
        @foreach ($errors->get($row->field) as $error)
            <span class="help-block">{{ $error }}</span>
        @endforeach
    @endif
</div>