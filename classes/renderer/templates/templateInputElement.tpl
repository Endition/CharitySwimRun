{if 
$type === "text" || 
$type === "number" || 
$type === "password"  ||
$type === "email" || 
$type === "date" } 
<div class="form-group">
    <label class="form-label">{$bezeichnung}</label>
    <div class="col-sm-8">
        <input class="form-control" 
            type="{$type}"
            value="{$value}" 
            name="{$name}"
            placeholder="{$placeholder}" 
            {if $id } id="{$id}" {/if} 
            {if $required === true} required {/if} 
            {if $maxlength > 0} maxlength="{$maxlength }" {/if} 
            {if $minlength > 0} minlength="{$minlength }" {/if} 
            {if $min > 0} min="{$min }" {/if} 
            {if $max > 0} max="{$max }" {/if} 
            {if $step > 0} step="{$step }" {/if} 
            {if $datamin } data-min="{$datamin}" {/if} 
            {if $onkeyup } onkeyup="{$onkeyup}" {/if} 
        >
    </div>
    {if $description } 
        <div class="form-text" id="basic-addon4">{$description}</div>
    {/if} 
</div>
{elseif $type == "select"} 
<div class="form-group">
    <label class="form-label">{$bezeichnung}</label>
    <div class="col-sm-8">
        <select class="form-control" 
                name="{$name}" 
                placeholder="{$placeholder}" 
                {if $id } id="{$id}" {/if} 
                {if $required === true} required {/if} 
                {if $multiple === true} multiple {/if} 

        >
        {if emptyElement==true}
            <option>bitte auswählen</option>
        {/if} 
        {html_options options=$selectValueList selected=$selectedElement}
        </select>
    </div>
</div>  
{elseif $type == "hidden"} 
    <input class="form-control" 
    type="{$type}"
    value="{$value}" 
    name="{$name}"
    {if $id } id="{$id}" {/if} 
    readonly="readonly"
    {if $required == true} required {/if} 
    >
{elseif $type == "submit"} 
<div class="form-group">
    <label class="form-label"></label>
    <div class="col-sm-8">
        <button type="submit" name="{$name}" class="btn btn-primary">{if $label} {$label} {else} Speichern {/if}</button>
        <button type="reset" class="btn btn-warning">Reset</button>
    </div>
</div> 
{elseif $type == "checkbox"} 
    <label class="form-label">{$bezeichnung}</label>
    <div class="col-sm-8">
        <input class="form-check-input" type="checkbox" value="" name="{$name}"  {if $required === true} required {/if} >
        <label class="form-check-label" for="flexCheckChecked">
        {$label}
        </label>
    </div>
{elseif $type == "autocomplete"} 
    <script type="text/javascript">
    jQuery(document).ready(function($){ 
        $("{$fieldId}").autocomplete({
            source: '{$source}',
            minLength: {$minLength}
            });
        });
    </script>
{/if} 