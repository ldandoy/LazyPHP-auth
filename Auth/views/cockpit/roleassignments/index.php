<h1 class="page-title">{{ pageTitle }}</h1>
<div class="box box-success">
    <div class="box-header">
        <h3 class="box-title">{{ boxTitle }}</h3>
    </div>
    <div class="box-body">
        {% form_open id="formGroup" action="formAction" class="form-horizontal" %}
            <div class="panel-group" id="roleassignment_accordion" role="tablist" aria-multiselectable="true">
                <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="roleassignment_accordion_group_heading">
                        <h4 class="panel-title">
                            <a role="button" data-toggle="collapse" data-parent="#cms_page_block_properties_accordion" href="#cms_page_block_properties_accordion_box" aria-expanded="false" aria-controls="cms_page_block_properties_accordion_box">
                                Boîte
                            </a>
                        </h4>
                    </div>
                    <div id="cms_page_block_properties_accordion_box" class="panel-collapse collapse" role="tabpanel" aria-labelledby="cms_page_block_properties_accordion_box_heading">
                        <div class="panel-body">
                            {% input_select name="fullwidth" label="Contenu pleine largeur" data-property-type="fullwidth" options="fullwidthOptions" %}
                            {% input_text name="id" label="Id" data-property-type="attribute" data-property-name="id" %}
                            {% input_text name="class" label="Class" data-property-type="attribute" data-property-name="class" %}
                            {% input_text name="height" label="Hauteur" data-property-type="style" data-property-name="height" %}
                        </div>
                    </div>
                </div>
<?php

foreach ($groups as $group) {

}

?>        
        {% form_close %}
    </div>
</div>
