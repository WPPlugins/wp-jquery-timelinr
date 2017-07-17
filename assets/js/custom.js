jQuery(document).ready(function() {
	/* DATEPICKER */     
	jQuery(".monthPicker").datepicker({ 
        dateFormat: 'yy-mm',
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        yearRange: '-1000:+100',
        showOn: "both",
        buttonImage: obj.image_url + "calendar-icon.png",
        buttonImageOnly: true,
 
        onClose: function() {
            var month = jQuery("#ui-datepicker-div .ui-datepicker-month :selected").val();
            var year = jQuery("#ui-datepicker-div .ui-datepicker-year :selected").val();
            jQuery(this).val(jQuery.datepicker.formatDate('yy-mm', new Date(year, month, 1)));
        }
    });
    
 
    jQuery(".monthPicker").focus(function () {
        jQuery(".ui-datepicker-calendar").hide();
        jQuery("#ui-datepicker-div").position({
            my: "center top",
            at: "center bottom",
            of: jQuery(this)
        });    
    });
});