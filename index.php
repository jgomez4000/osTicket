<?php
/*********************************************************************
    index.php

    Helpdesk landing page. Please customize it to fit your needs.

    Peter Rotich <peter@osticket.com>
    Copyright (c)  2006-2013 osTicket
    http://www.osticket.com

    Released under the GNU General Public License WITHOUT ANY WARRANTY.
    See LICENSE.TXT for details.

    vim: expandtab sw=4 ts=4 sts=4:
**********************************************************************/
require('client.inc.php');

require_once INCLUDE_DIR . 'class.page.php';

$section = 'home';
require(CLIENTINC_DIR.'header.inc.php');
?>
</div>
<div class="container-fluid">
<div class="row support-image">
    
      <div style="flex-basis: 612px;"><h2 style="font-family: Lato;">| <?php echo __('QUICK AND EFFICIENT SUPPORT') ?> |</h2>
<?php

    if($cfg && ($page = $cfg->getLandingPage()))
        echo $page->getBodyWithImages();
    else
        echo  '<h1>'.__('Quick and Efficient Support').'</h1>';
    ?></div>
    <div>
        <img src="<?php echo ASSETS_PATH; ?>images/support.jpg" alt="Quick and Efficient Support">
    </div>
</div>
</div>
<div class="container">
<div class="row front-boxes">
<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <div class="outer_box_green" style="border-color: #879EAA;">
                        <div class="box-body">
                            <h1 style="font-family: Lato; color: #333;"><?php echo __('Open a New Ticket') ?></h1>
                            <p style="font-family: Lato; font-weight: light;"><?php echo __('Please provide as much detail as possible so we can best assist you.<br>To update a previously submitted ticket, please login.') ?></p>
                            <a class="btn btn-success" style="background-color: #547B97; border-color: #547B97; border-radius: 10px;" href="<?php echo ROOT_PATH; ?>open.php"><?php echo __('Open a New Ticket') ?></a> <br><br>
                        </div>
                    </div>
                </div>
  <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <div class="outer_box_blue" style="border-color: #879EAA;">
                        <div class="box-body">
                            <h1 style="font-family: Lato; color: #333;"><?php echo __('Check Ticket Status') ?></h1>
                            <p style="font-family: Lato; font-weight: light;"><?php echo __('We provide archives and history of all your current and past support requests complete with responses.') ?></p>
                            <a class="btn btn-primary" style="background-color: #B4C1BA; border-color: #B4C1BA; border-radius: 10px;" href="<?php echo ROOT_PATH; ?>view.php"><?php echo __('Check Ticket Status') ?></a> <br><br>
                        </div>
                    </div>
                </div>  
</div>
    
<?php require(CLIENTINC_DIR.'footer.inc.php'); ?>
