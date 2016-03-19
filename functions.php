<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;

function themeConfig($form) {
    $logoUrl = new Typecho_Widget_Helper_Form_Element_Text('logoUrl', NULL, NULL, _t('站点LOGO地址'), _t('在这里填入一个图片URL地址, 以在网站标题前加上一个LOGO'));
    $form->addInput($logoUrl);

    $db = Typecho_Db::get();
    $pages=$db->fetchAll($db->select('slug,title')->from('table.contents')->where('type=?','page'));
    $list=array('ShowRecentPosts' => _t('显示最新文章'),
    'ShowRecentComments' => _t('显示最近回复'),
    'ShowCategory' => _t('显示分类'),
    'ShowArchive' => _t('显示归档'),
    'ShowOther' => _t('显示其它杂项'));
    foreach ($pages as $key => $value) {
      $list[$value['slug']] = $value['title'];
    }

    $sidebarBlock = new Typecho_Widget_Helper_Form_Element_Checkbox('sidebarBlock',
    $list,
    array('ShowRecentPosts', 'ShowRecentComments', 'ShowCategory', 'ShowArchive', 'ShowOther'), _t('侧边栏显示'));

    $form->addInput($sidebarBlock->multiMode());
}

function PageToLinks($slug = 'links')
{
    $db = Typecho_Db::get();

    $contents = $db->fetchObject($db->select('text')->from('table.contents')
    ->where('slug = ?', $slug)->limit(1));
    if (!$contents) {
        return;
    }
    $text = $contents->text;
    $titles = $db->fetchObject($db->select('title')->from('table.contents')
    ->where('slug = ?', $slug)->limit(1));
    $title=$titles->title;
    if(substr($text, -1)!=" "){
        $text=$text." ";
    }
    preg_match_all("/\[(.*?)\]\[(\d)\]/", $text,$r);
    echo "<h3 class='widget-title'>".$title."</h3>";
    echo "<ul class='widget-list'>";
    foreach ($r[1] as $key => $value) {
        $num=$r[2][$key];
        preg_match_all("/\[$num\]:\s(.*?)\s/", $text, $urls);
        $href="<a href=".$urls[1][0].">".$value."</a>";
        echo "<li>".$href."</li>";
    }
    echo "</ul>";
}
/*
function themeFields($layout) {
    $logoUrl = new Typecho_Widget_Helper_Form_Element_Text('logoUrl', NULL, NULL, _t('站点LOGO地址'), _t('在这里填入一个图片URL地址, 以在网站标题前加上一个LOGO'));
    $layout->addItem($logoUrl);
}
*/
