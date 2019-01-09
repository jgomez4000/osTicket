<div class="row">
    <div class="col-md-12">
    <h1><?php echo __('Knowledgebase'); ?></h1>

<?php
    $categories = Category::objects()
        ->exclude(Q::any(array(
            'ispublic'=>Category::VISIBILITY_PRIVATE,
            'faqs__ispublished'=>FAQ::VISIBILITY_PRIVATE,
        )))
        ->annotate(array('faq_count'=>SqlAggregate::COUNT('faqs')))
        ->filter(array('faq_count__gt'=>0));
    if ($categories->exists(true)) { ?>
    <p><?php echo __('Click on the category to browse FAQs.'); ?></p></div>
        <div class="col-md-9">
        <ul id="kb" class="list-group">
<?php
        foreach ($categories as $C) { ?>
            <li class="list-group-item">
                <div class="row">
                <div class="col-xs-3 col-md-2"><i class="fas fa-folder-open"></i></div>
            <div class="col-xs-9 col-md-10">
            <h4 class="list-group-item-heading"><?php echo sprintf('<a href="faq.php?cid=%d">%s (%d)</a>',
                $C->getId(), Format::htmlchars($C->getLocalName()), $C->faq_count); ?></h4>
            <div class="faded list-group-item-text" style="margin:10px 0">
                <?php echo Format::safe_html($C->getLocalDescriptionWithImages()); ?>
            </div>
                <div class="rectangle-list">
                <ol>
<?php       foreach ($C->faqs
                    ->exclude(array('ispublished'=>FAQ::VISIBILITY_PRIVATE))
                    ->limit(5) as $F) { ?>
                <li>
                <a href="faq.php?id=<?php echo $F->getId(); ?>">
                <?php echo $F->getLocalQuestion() ?: $F->getQuestion(); ?>
                </a></li>
<?php       } ?> </ol></div>
            </div></div>
            </li>
<?php   } ?>
       </ul>
<?php
    } else {
        echo __('NO FAQs found');
    }
?>
        </div><div class="col-md-3">
    <div class="sidebar">
    <div class="searchbar">
        <form method="get" action="faq.php">
        <input type="hidden" name="a" value="search"/>
        <select class="form-control" name="topicId"
            onchange="javascript:this.form.submit();">
            <option value="">‚Äî<?php echo __("Browse by Topic"); ?>‚Äî</option>
<?php
$topics = Topic::objects()
    ->annotate(array('has_faqs'=>SqlAggregate::COUNT('faqs')))
    ->filter(array('has_faqs__gt'=>0));
foreach ($topics as $T) { ?>
        <option value="<?php echo $T->getId(); ?>"><?php echo $T->getFullName();
            ?></option>
<?php } ?>
        </select>
        </form>
    </div>
    <br/>
    </div>
</div>
</div>
