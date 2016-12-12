<?php
$item = d(@$args[1], NULL);
$city = d(@$item['city'], '');
?>
<link rel="stylesheet" href="/public/css/multiple-select.css">
<div class="control-group">
    <label class="control-label col-sm-2">
        <i>*</i>地域选择
    </label>
    <div class="controls">
    <input type="hidden" id="city" name="city">
    <select id="location" name="location"  multiple="multiple">

<?php

$province = F::$f->Model_Location->getProvinceCity();

foreach($province[0] as $pcode => $pname){
    echo("<optgroup label='".$pname."'>");
    foreach($province[1][$pcode] as $ccode => $cname){
        echo("<option value='".$pcode."-".$ccode."'>".$cname."</option>");
    }
    echo("/optgroup");
}
?>
</select>
    </div>
</div>

<script src="/public/js/multiple-select.js"></script>
<script>

    var city = '<?=$city?>';
    if(city) {
        $('#location').val(city.split(","));
    }

    $("#location").change(function() {
        $("#city").val($(this).val());
    }).multipleSelect({
        filter: true,
        multiple: true
    });
</script>
