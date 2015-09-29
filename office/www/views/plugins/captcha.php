<?php if( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script type="text/javascript" src="https://www.google.com/recaptcha/api/challenge?k=<?php echo $this->config->item('google_captcha_id_public'); ?>"></script>
<script type="text/javascript">
     var RecaptchaOptions = {
         theme : 'white',/*'red','white','blackglass','clean','custom'*/
         //custom_theme_widget: 'recaptcha_widget',
         lang : 'en',
         custom_translations:null,
         tabindex : 2         
     };
</script>