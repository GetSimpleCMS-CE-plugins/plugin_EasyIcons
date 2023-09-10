<?php

# get correct id for plugin
$thisfile = basename(__FILE__, ".php");

i18n_merge('easyIcons') || i18n_merge('easyIcons', 'en_US');


# register plugin
register_plugin(
    $thisfile, //Plugin id
    'EasyIcons',     //Plugin name
    '1.0',         //Plugin version
    'Multicolor',  //Plugin author
    'http://bit.ly/donate-multicolor-plugins', //author website
    'Use Shortcode on Ckeditor for support FontAwesome or Unicons', //Plugin description
    'plugins', //page type - on which admin tab to display
    'easyIcons'  //main function (administration)
);



add_action('theme-header', 'easyIconsStyle');

function easyIconsStyle()
{
    $file = GSDATAOTHERPATH . '/easyicons/settings.json';

    $turnOn = 'yes';
    $type = 'fontawesoem';

    if (is_dir(GSDATAOTHERPATH . '/easyicons/') && file_exists($file)) {
        $data = json_decode(file_get_contents($file));
        $turnOn = $data->turnon;
        $type = $data->type;
    };


    if ($turnOn == 'yes') {

        if ($type == 'fontawesome') {
            echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />';
        } else {
            echo '<link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">';
        };
    };
};

# add a link in the admin tab 'theme'
add_action('plugins-sidebar', 'createSideMenu', array($thisfile, 'EasyIcons 😏'));


function easyIcons()
{



    echo "

    <h3>EasyIcons</h3>

    <div style='width:100%;background:#fafafa;padding:10px;box-sizing:boder-box;border:solid 1px #ddd;'>


    
     <p  style='margin:0;margin-bottom:5px;padding:0;'>1." . i18n_r('easyIcons/ONE') . "</p>
    <p style='margin:0;margin-bottom:5px;padding:0;'>2." . i18n_r('easyIcons/TWO') . "</p>
    <p style='margin:0;margin-bottom:5px;padding:0'>3." . i18n_r('easyIcons/THREE') . " </p>
    <code style='font-weight:bold;color:white;background:red;display:block;width:100%;padding:10px;border:solid 1px;'>[ei icon='fa-solid fa-house' size='60px' color='red']</code>


    </div>
    
<hr style='margin-top:20px;margin-bottom:20px;opacity:0.3;'>

    <form method='POST'>
    <h3 style='margin:0;margin-top:10px;'>" . i18n_r('easyIcons/TURNONCSS') . "</h3>

    <select name='turnoncss' class='turnoncss' style='width:100%;padding:10px;margin:10px 0;box-sizing:border-box;background:#fff;border:solid 1px #ddd;'>
    <option name='yes'>yes</option>
    <option name='no'>no</option>
    </select>

    <h3 style='margin:0;margin-top:10px;'>" . i18n_r('easyIcons/CSSTYPE') . "</h3>
    <select name='csstype' class='csstype' style='width:100%;padding:10px;margin:10px 0;box-sizing:border-box;background:#fff;border:solid 1px #ddd;'>
<option value='fontawesome'>Font Awesome </option>
<option value='unicons'>UniCons </option>
    </select>


    <input name='save-easyicon' value='" . i18n_r('easyIcons/SAVEOPTIONS') . " 💾' type='submit' style='border:solid 1px #000;background:#000;padding:10px 15px;color:#fff;display:block;margin-top:20px;'>
    </form>

    <hr style='margin-top:20px;margin-bottom:20px;opacity:0.3;'>
    ";

    echo '<div id="paypal" style="margin-top:10px; background: #fafafa; border:solid 1px #ddd; padding: 10px;box-sizing: border-box; text-align: center;">
    <p style="margin-bottom:10px;">' . i18n_r('easyIcons/PAYPAL') . ' </p>
    <a href="https://www.paypal.com/donate/?hosted_button_id=TW6PXVCTM5A72"><img alt="" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif" border="0"></a>
</div>';

    $file = GSDATAOTHERPATH . '/easyicons/settings.json';


    if (is_dir(GSDATAOTHERPATH . '/easyicons/')) {

        if (file_exists($file)) {
            $data = json_decode(file_get_contents($file));

            echo '
            <script>
            document.querySelector(".turnoncss").value = "' . $data->turnon . '";
            document.querySelector(".csstype").value = "' . $data->type . '";
            </script>
            ';
        };


    } else {
        mkdir(GSDATAOTHERPATH . '/easyicons/', 0755);
        file_put_contents(GSDATAOTHERPATH . '/easyicons/.htaccess', 'Deny from all');
    };



    if (isset($_POST['save-easyicon'])) {

        $turnoncss = $_POST['turnoncss'];
        $csstype = $_POST['csstype'];
        $content = array("turnon" => $turnoncss, "type" => $csstype);
        $finaldata = json_encode($content);
        file_put_contents($file, $finaldata);
        echo ("<meta http-equiv='refresh' content='0'>");
    };
}




add_action('theme-header', 'eaf');


function eaf()
{



    $pattern = "/\[ei\sicon\W(.*)\W\ssize=\W(.*)\W color=\W(.*)\W]/i";

    function patterneaf($match)
    {


        $match[1] = substr(str_replace("'", "", htmlspecialchars_decode($match[1])), 0, -5);
        $match[2] = substr(str_replace("'", "", htmlspecialchars_decode($match[2])), 5, -5);
        $match[3] = substr(str_replace("'", "", htmlspecialchars_decode($match[3])), 5, -5);



        return "<i class='$match[1]' style='font-size: $match[2]; color: $match[3];'></i>";
    };

    global $content;

    ///shortbox create
    $newcontent = preg_replace_callback(
        $pattern,
        'patterneaf',
        $content
    );


    $content = $newcontent;
}
