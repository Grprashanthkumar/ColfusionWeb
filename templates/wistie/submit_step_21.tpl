<link href="{$my_pligg_base}/css/bootstrap.min.css" rel="stylesheet" />
<link href="{$my_pligg_base}/css/bootstrap-wizard.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="{$my_pligg_base}/css/font-awesome.css" />
<link rel="stylesheet" type="text/css" href="jquery.sheet.css" />
<link rel="stylesheet" type="text/css" href="{$my_pligg_base}/css/smoothness/jquery-ui-1.10.3.custom.min.css" />
<link rel="stylesheet" type="text/css" href="{$my_pligg_base}/templates/{$the_template}/css/style.css" media="screen" />
<link rel="stylesheet" type="text/css" href="{$my_pligg_base}/templates/{$the_template}/css/wizard.css" />
<link rel="stylesheet" type="text/css" href="{$my_pligg_base}/templates/{$the_template}/css/submit1.css" />
<link rel="stylesheet" type="text/css" href="{$my_pligg_base}/css/importWizard/sourceSelectionStep.css" />
<link rel="stylesheet" type="text/css" href="{$my_pligg_base}/css/bootstrap-lightbox.min.css" />
<link rel="stylesheet" type="text/css" href="{$my_pligg_base}/css/sourceAttachment.css" />
<link rel="stylesheet" type="text/css" href="{$my_pligg_base}/css/dataTableModel.css" />
<link rel="stylesheet" type="text/css" href="{$my_pligg_base}/css/addRelationship.css" />
<link rel="stylesheet" type="text/css" href="{$my_pligg_base}/css/relationship.css" />
<link rel="stylesheet" type="text/css" href="{$my_pligg_base}/css/typeahead.js-bootstrap.css" />
<link rel="stylesheet" type="text/css" href="{$my_pligg_base}/css/parsley.css" />
<link rel="stylesheet" type="text/css" href="{$my_pligg_base}/css/parsley_custom.css" />

<script type="text/javascript" src="{$my_pligg_base}/templates/{$the_template}/js/story_preview.js"></script>
<script type="text/javascript" src="{$my_pligg_base}/javascripts/jquery-1.9.1.js"></script>
<script type="text/javascript" src="{$my_pligg_base}/javascripts/jquery-ui-1.10.3.custom.min.js"></script>
<script type="text/javascript" src="{$my_pligg_base}/javascripts/chosen.jquery.js"></script>
<script type="text/javascript" src="{$my_pligg_base}/javascripts/bootstrap.min.js"></script>
<script type="text/javascript" src="{$my_pligg_base}/javascripts/bootstrap-wizard.js"></script>
<script type="text/javascript" src="{$my_pligg_base}/javascripts/bootstrap-lightbox.js"></script>
<script type="text/javascript" src="{$my_pligg_base}/javascripts/hogan.min.js"></script>
<script type="text/javascript" src="{$my_pligg_base}/javascripts/typeahead.min.js"></script>
<script type="text/javascript" src="{$my_pligg_base}/javascripts/jquery.sheet.js"></script>
<script type="text/javascript" src="{$my_pligg_base}/templates/{$the_template}/js/jquery.form.js"></script>
<script type="text/javascript" src="{$my_pligg_base}/javascripts/jquery-file-upload/jquery.fileupload.js"></script>
<script type="text/javascript" src="{$my_pligg_base}/javascripts/jquery-file-upload/jquery.fileupload-ui.js"></script>
<script type="text/javascript" src="{$my_pligg_base}/javascripts/jquery-file-upload/jquery.iframe-transport.js"></script>
<script type="text/javascript" src="{$my_pligg_base}/javascripts/jquery-file-upload/vendor/jquery.ui.widget.js"></script>
<script type="text/javascript" src="{$my_pligg_base}/javascripts/parsley.min.js"></script>
<script type="text/javascript" src="{$my_pligg_base}/javascripts/persist-min.js"></script>

<script type="text/javascript" src="{$my_pligg_base}/javascripts/dataSourceUtil.js"></script>
<script type="text/javascript" src="{$my_pligg_base}/javascripts/fileManager.js"></script>

<script type="text/javascript" src="{$my_pligg_base}/javascripts/knockout-2.3.0.js"></script>
<script type="text/javascript" src="{$my_pligg_base}/javascripts/knockout.mapping.js"></script>
<script type="text/javascript" src="{$my_pligg_base}/javascripts/knockout_models/Utils.js"></script>
<script type="text/javascript" src="{$my_pligg_base}/javascripts/knockout_models/RelationshipModel.js"></script>
<script type="text/javascript" src="{$my_pligg_base}/javascripts/knockout_models/DataPreviewViewModel.js"></script>
<script type="text/javascript" src="{$my_pligg_base}/javascripts/knockout_models/RelationshipViewModel.js"></script>
<script type="text/javascript" src="{$my_pligg_base}/javascripts/knockout_models/NewRelationshipViewModel.js"></script>
<script type="text/javascript" src="{$my_pligg_base}/javascripts/knockout_models/WizardExcelPreviewViewModel.js"></script>
<script type="text/javascript" src="{$my_pligg_base}/javascripts/knockout_models/SourceWorksheetSettingsViewModel.js"></script>
<script type="text/javascript" src="{$my_pligg_base}/javascripts/knockout_models/ProgressBarViewModel.js"></script>

<script type="text/javascript" src="{$my_pligg_base}/templates/{$the_template}/js/importWizard.js"></script>
<script type="text/javascript" src="{$my_pligg_base}/templates/{$the_template}/js/wizardFromFile.js"></script>
<script type="text/javascript" src="{$my_pligg_base}/templates/{$the_template}/js/wizardFromDB.js"></script>



{literal}
    <link href="chosen.css" rel="stylesheet" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <style>
        #newRelationshipBtn{
            margin: 0 0 10px 0; 
            display: block;
        }
    </style>

    <script type="text/javascript">
        (function($) {
        $.widget("custom.combobox", {
        _create: function() {
        this.wrapper = $("<span>")
        .addClass("custom-combobox")
        .insertAfter(this.element);

        this.element.hide();
        this._createAutocomplete();
        this._createShowAllButton();
        },
        _createAutocomplete: function() {
        var selected = this.element.children(":selected"),
        value = selected.val() ? selected.text() : "";

        this.input = $("<input>")
        .appendTo(this.wrapper)
        .val(value)
        .attr("title", "")
        .addClass("custom-combobox-input ui-widget ui-widget-content ui-state-default ui-corner-left")
        .autocomplete({
        delay: 0,
        minLength: 0,
        source: $.proxy(this, "_source")
        })
        .tooltip({
        tooltipClass: "ui-state-highlight"
        });

        this._on(this.input, {
        autocompleteselect: function(event, ui) {
        ui.item.option.selected = true;
        this._trigger("select", event, {
        item: ui.item.option
        });
        },
        autocompletechange: "_removeIfInvalid"
        });
        },
        _createShowAllButton: function() {
        var input = this.input,
        wasOpen = false;

        $("<a>")
        .attr("tabIndex", -1)
        .attr("title", "Show All Items")
        .tooltip()
        .appendTo(this.wrapper)
        .button({
        icons: {
        primary: "ui-icon-triangle-1-s"
        },
        text: false
        })
        .removeClass("ui-corner-all")
        .addClass("custom-combobox-toggle ui-corner-right")
        .mousedown(function() {
        wasOpen = input.autocomplete("widget").is(":visible");
        })
        .click(function() {
        input.focus();

        // Close if already visible
        if (wasOpen) {
        return;
        }

        // Pass empty string as value to search for, displaying all results
        input.autocomplete("search", "");
        });
        },
        _source: function(request, response) {
        var matcher = new RegExp($.ui.autocomplete.escapeRegex(request.term), "i");
        response(this.element.children("option").map(function() {
        var text = $(this).text();
        if (this.value && (!request.term || matcher.test(text)))
        return {
        label: text,
        value: text,
        option: this
        };
        }));
        },
        _removeIfInvalid: function(event, ui) {

        // Selected an item, nothing to do
        if (ui.item) {
        return;
        }

        // Search for a match (case-insensitive)
        var value = this.input.val(),
        valueLowerCase = value.toLowerCase(),
        valid = false;
        this.element.children("option").each(function() {
        if ($(this).text().toLowerCase() === valueLowerCase) {
        this.selected = valid = true;
        return false;
        }
        });

        // Found a match, nothing to do
        if (valid) {
        return;
        }

        // Remove invalid value
        this.input
        .val("")
        .attr("title", value + " didn't match any item")
        .tooltip("open");
        this.element.val("");
        this._delay(function() {
        this.input.tooltip("close").attr("title", "");
        }, 2500);
        this.input.data("ui-autocomplete").term = "";
        },
        _destroy: function() {
        this.wrapper.remove();
        this.element.show();
        }
        });
        })(jQuery);

        $.sheet.preLoad('jquery.sheet/');

        var sid;
        var dataPreviewViewModel;
        var relationshipViewModel;

        $(document).ready(function() {

        sid = $('#sid').val();
        dataPreviewViewModel = new DataPreviewViewModel(sid);
        relationshipViewModel = new RelationshipViewModel(sid);
        ko.applyBindings(relationshipViewModel, document.getElementById("mineRelationshipsContainer"));

        $.ajax({
            type: 'POST',
            url: "DataImportWizard/wizardMarkup.php",
            data: {},
            success: function(data) {
                $("#wizardWrapper").append(data);
                importWizard.Init();
            },
            dataType: 'html',
            async: false
        });

        $.ajax({
            type: 'POST',
            url: "DataImportWizard/dataPreviewMarkup.php",
            data: {},
            success: function(data) {
                $("#dataPreviewContainer").append(data);
                ko.applyBindings(dataPreviewViewModel, document.getElementById("dataPreviewContainer"));
            },
            dataType: 'html',
            async: false
        });

        // Open attachment upload page.
        $('#uploadAttachmentLink').click(function() {
        $('#uploadAttachmentLightBox').lightbox({resizeToFit: false});
        });

        // When lightbox is closed, refresh attachment list.
        $('#uploadAttachmentLightBox').bind('hidden', function(e) {
        // Refresh attachment list.
        fileManager.loadSourceAttachments(sid, $("#attachmentList"));
        var lightBoxContentDom = $('#uploadAttachmentLightBox').find('.lightbox-content');
        var iframeDom = $(lightBoxContentDom).find('iframe');
        $(iframeDom).remove();
        $(iframeDom).appendTo(lightBoxContentDom);
        });

        // Load attachment list.
        fileManager.loadSourceAttachments(sid, $("#attachmentList"));          
        });

        function submitDataSubmissionForm() {
            $('#thisform').parsley({
                'excluded': 'input[type=radio], input[type=checkbox]'
            });
            
            if($('#thisform').parsley('validate')){
                document.forms['thisform'].submit();
            }
        }     
    </script>

{/literal}

<h1 style="font-size: 24px; font-weight: bold; padding-left: 10px; margin-bottom: 0;">{#PLIGG_Visual_Submit2_Header#}</h1>



<div id="submit">
    <div id="submit_step_1_content">
        {checkActionsTpl location="tpl_pligg_submit_step2_start"}
        <form onsubmit="return checkDataForm(this)" action="submit1.php" method="post" name="thisform" id="thisform" enctype="multipart/form-data">

            <div class="submit_step_1_left">
                <h4 class="stepHeader">Step 1: Describe Your Data</h4>
                {if $Submit_Show_URL_Input eq 1}
                    <h2>{#PLIGG_Visual_Submit2_Source#}</h2>
                    <label>{#PLIGG_Visual_Submit2_NewsURL#}: </label>
                    <a href="{$submit_url}" class="simple">{$submit_url}</a>
                    {if $submit_url_title neq "1"}
                        <br />
                        <label>{#PLIGG_Visual_2_URLTitle#}: </label>{$submit_url_title}
                    {/if}
                {/if}

<!--<h2 style="font-size: 14px; color: #4a9bea; margin: 4px 0 0 0; font-weight: bold">{#PLIGG_Visual_Submit2_Details#}</h2>-->

                <table id="step1_table">
                    <tr>
                        <td class="step1_input_title">{#PLIGG_Visual_Submit2_Title#}:</td>
                        <td>
                            <input data-required="true" type="text" id="title" class="text" name="title" value="{if $submit_title}{$submit_title}{else}{$submit_url_title}{/if}" size="54" maxlength="{$maxTitleLength}" />
                        </td>
                    </tr>
                    <tr>
                        <td class="step1_input_title">{#PLIGG_Visual_Submit2_Description#}:</td>
                        <td>
                            <textarea data-required="true" name="bodytext" class="bodytext" rows="10" cols="41" id="bodytext" maxlength="{$maxStoryLength}" WRAP="SOFT" onkeypress="counter(this)" onkeydown="counter(this)" onkeyup="counter(this);
                if (!this.form.summarycheckbox || !this.form.summarytext)
                    return;
                if (this.form.summarycheckbox.checked == false) {ldelim}
                    this.form.summarytext.value = this.form.bodytext.value.substring(0, {$StorySummary_ContentTruncate});{rdelim}
                textCounter(this.form.summarytext, this.form.remLen, {$StorySummary_ContentTruncate});">{if $submit_url_description}{$submit_url_description}{/if}{$submit_content}</textarea><br />
                        </td>
                    </tr>

                   
                    {if $enable_tags}
                        <tr>
                            <td class="step1_input_title" style="vertical-align: top;"><span style="margin-top: 5px">{#PLIGG_Visual_Submit2_Tags#}:</span></td>
                            <td>
                                <input  type="text" id="tags" class="wickEnabled" name="tags" value="{$tags_words}" size="54" maxlength="{$maxTagsLength}" /><br /><br />
                                <script type="text/javascript" language="JavaScript" src="{$my_pligg_base}/templates/{$the_template}/js/tag_data.js"></script> 
                                <script type="txt/javascript" language="JavaScript" src="{$my_pligg_base}/templates/{$the_template}/js/wick.js"></script> 
                            </td>
                        </tr>
                    {/if}
                    <tr>
                        <td class="step1_input_title" style="vertical-align: top;">
                            <span style="margin-top: 5px">{#PLIGG_Visual_Submit2_Attachments#}:</span>
                        </td>
                        <td style="vertical-align: top;">
                            <ul class="fileList" id="attachmentList"> 
                                <li>
                                    <span id="attachmentLoadingIcon"><img src="{$my_pligg_base}/images/ajax-loader.gif"/></span>
                                </li>
                                <li>
                                    <span><a id="uploadAttachmentLink" style="color: #a44848;"><i class="icon-cloud-upload" style="margin-right: 5px;"></i>Add Files... (NOT THE DATA YET)</a></span>
                                </li>
                            </ul>                  
                        </td>
                    </tr>
                </table>

                <h4 class="stepHeader">Step 2: Upload Your Data</h4>
                <span id='open-wizard' class='btn btn-primary'>Import data</span>
                <div class="submit_right_sidebar hidden" id="dockcontent">
                    {checkActionsTpl location="tpl_pligg_submit_preview_start"}

                    <div id="dataPreviewContainer">        
                    </div>

                    <div class="storyfooter" style="float:right;font-size:10px;">	
                        <div id="lp-category">
                            {section name=thecat loop=$submit_cat_array}
                            {if $submit_cat_array[thecat].auto_id == $submit_category}{$submit_cat_array[thecat].name}{/if}
                        {/section}
                    </div>| 
                    <div id="lp-tags">{$tags_words}</div>
                </div>

                <div style="clear:both;"></div>


                {checkActionsTpl location="tpl_pligg_submit_step2_end"}

                <!--<div id="wizrd"> </div>-->	
                <div id="wizardWrapper">

                </div>


            </div>
        </div>

       

        <div style="clear:both;"></div>

        <input type="hidden" name="url" id="url" value="{$submit_url}" />
        <input type="hidden" name="phase" value="1" />
        <input type="hidden" name="randkey" value="{$randkey}" />
        <input type="hidden" name="id" value="{$submit_id}" />
        <input type="hidden" id="sid" name="sid" value="{$sid}"/>
    </form>	

     <div id="dataSubmissionStep3Container" class="hidden">
            <h4 class="stepHeader">Step 3: Connect To The Puzzle (Optional)</h4>
            <div id="step3Wrapper">
                <div id="step3Container">    
                    {include file='relationships.tpl'}
                </div>
            </div>
            {include file='addRelationships.tpl'}
        </div>
    

    {literal}
        <script>
            $('#newRelContainer').css({
            'width': '102%',
            'margin-left': '-22px'
            });
        </script>
    {/literal}
    <input onclick="submitDataSubmissionForm()" class="button_max" type="submit" value="{#PLIGG_Visual_Submit2_Continue#}" id ="final"  disabled = true />
</div>	
</div>
<div id="uploadAttachmentLightBox" class="lightbox hide fade"  tabindex="-1" role="dialog" aria-hidden="true">
    <div class='lightbox-content'>
        <div class="pull-right">
            <button class="btn btn-link" type="button" data-dismiss="lightbox" aria-hidden="true">Close this dialog &times;</button>
               
        </div>
        <div>
            <iframe width="1000" height="500" src="{$my_pligg_base}/fileManagers/sourceAttachmentUploadPage.php?sid={$sid}"></iframe>
        </div>
    </div>
</div>
