<?php
if(!defined('OSTCLIENTINC')) die('Access Denied!');
$info=array();
if($thisclient && $thisclient->isValid()) {
    $info=array('name'=>$thisclient->getName(),
                'email'=>$thisclient->getEmail(),
                'phone'=>$thisclient->getPhoneNumber());
}

$info=($_POST && $errors)?Format::htmlchars($_POST):$info;

$form = null;
if (!$info['topicId']) {
    $iter = 0;
    while (array_key_exists('topicId'.$iter, $_GET)) {
        if (preg_match('/^\d+$/',$_GET['topicId'.$iter]) && Topic::lookup($_GET['topicId'.$iter])) {
            $info['topicId'] = intval($_GET['topicId'.$iter]);
        }
        ++$iter;
    }
    if (!$info['topicId']) {
        $info['topicId'] = $cfg->getDefaultTopicId();
    }
}

$forms = array();
if ($info['topicId'] && ($topic=Topic::lookup($info['topicId']))) {
    foreach ($topic->getForms() as $F) {
        if (!$F->hasAnyVisibleFields())
            continue;
        if ($_POST) {
            $F = $F->instanciate();
            $F->isValidForClient();
        }
        $forms[] = $F;
    }
}

?>
<div class="row" style="font-family: Lato;">
<div class="col-md-12">
<h1 style="color: #4E6975; font-size: 21px; padding: 10px 0px 10px 10px;"><?php echo __('Open a New Ticket');?></h1>
<p><?php echo __('Please fill in the form below to open a new ticket.');?></p>
<form id="ticketForm" method="post" action="open.php" enctype="multipart/form-data" class="form-horizontal">
  <?php csrf_token(); ?>
  <input type="hidden" name="a" value="open">
  <table>
<?php
        if (!$thisclient) {
            $uform = UserForm::getUserForm()->getForm($_POST);
            if ($_POST) $uform->isValid();
            $uform->render(false);
        }
        else { ?>
            <div class="form-group">
          <div class="col-sm-1"><label for="email"><?php echo __('Email'); ?>:</label></div><div class="col-sm-11">   
            <?php
            echo $thisclient->getEmail(); ?> </div></div>
        <div class="form-group">
            <div class="col-sm-1"><label for="email"> <?php echo __('Client'); ?>:</label></div><div class="col-sm-11"> <?php
            echo Format::htmlchars($thisclient->getName()); ?>
        <?php } ?></div></div>
<hr/>
            <div class="form-group">
                    <label class="control-label col-sm-2"> <?php echo __('Help Topic*&nbsp;'); ?></label>
<div class="col-sm-10">
            <?php
            $topics=Topic::getPublicHelpTopics(true, $thisclient);
            $iter = 0;
            
            if ($topics) {
                $topicValues = $topics[$iter]['topics'];
            } else {
                $topicValues = array();
            }
            
            ?>
            <select class="form-control" id="<?= "topicId0" ?>" name="<?= "topicId0" ?>" onchange="javascript:
                    
                    var data = $(':input[name]', '#dynamic-form').serialize();
                    $.ajax(
                      'ajax.php/form/help-topic/' + this.value,
                      {
                        data: data,
                        dataType: 'json',
                        success: function(json) {
                          var currentRowSelect = $('#topicId0').closest('div.col-sm-10');
                          if (json.hasNestedChilds) {
                            currentRowSelect.nextAll().remove();
                            currentRowSelect.after(json.html);
                            $('#dynamic-form').empty();
                          } else {
                            currentRowSelect.nextAll().remove();
                            $('#dynamic-form').empty().append(json.html);
                          }
                          $(document.head).append(json.media);
                        }
                      });
            ">
                <option value="" selected="selected">&mdash; <?php echo __('Select a Help Topic');?> &mdash;</option>
                <?php
                if(count($topicValues) > 0) {
                    foreach($topicValues as $id =>$topicInfo) {
                        echo sprintf('<option value="%d" %s>%s</option>',
                                $id, ($info['topicId']==$id)?'selected="selected"':'', $topicInfo['name']);
                    }
                } else { ?>
                    <option value="0" ><?php echo __('General Inquiry');?></option>
                <?php
                } ?>
            </select>
            <font class="error"><?php echo $errors['topicId']; ?></font></div></div>
    <div id="dynamic-form">
        <?php foreach ($forms as $form) {
            include(CLIENTINC_DIR . 'templates/dynamic-form.tmpl.php');
        } ?>
    </div>
<table>
    <tbody>
    <?php
    if($cfg && $cfg->isCaptchaEnabled() && (!$thisclient || !$thisclient->isValid())) {
        if($_POST && $errors && !$errors['captcha'])
            $errors['captcha']=__('Please re-enter the text again');
        ?>
    <div class="captchaRow form-group">
        <label class="col-sm-2 text-right"><?php echo __('CAPTCHA Text');?></label>
        <div class="col-sm-10">
            <span class="captcha"><img src="captcha.php" border="0" align="left"></span>
            &nbsp;&nbsp;
            <input id="captcha" type="text" name="captcha" size="6" autocomplete="off">
            <em><?php echo __('Enter the text shown on the image.');?></em>
            <font class="error">*&nbsp;<?php echo $errors['captcha']; ?></font>
        </div>
    </div>
    <?php
    } ?>

    </tbody>
  </table>
<hr/>
  <p class="buttons" style="text-align:center;">
        <input class="btn btn-success" type="submit" value="<?php echo __('Create Ticket');?>">
        <input class="btn btn-warning" type="reset" name="reset" value="<?php echo __('Reset');?>">
        <input class="btn btn-danger" type="button" name="cancel" value="<?php echo __('Cancel'); ?>" onclick="javascript:
            $('.richtext').each(function() {
                var redactor = $(this).data('redactor');
                if (redactor && redactor.opts.draftDelete)
                    redactor.deleteDraft();
            });
            window.location.href='index.php';">
  </p>

</form>
  <br><br>
   </div>
  </div>