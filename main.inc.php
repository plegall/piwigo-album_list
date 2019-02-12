<?php
/*
Plugin Name: Album List
Version: auto
Description: List all albums on a dedicated page
Plugin URI: https://piwigo.org/ext/extension_view.php?eid=
Author: plg
Author URI: https://piwigo.org
*/
if (!defined('PHPWG_ROOT_PATH'))
{
  die('Hacking attempt!');
}

if (basename(dirname(__FILE__)) != 'album_list')
{
  add_event_handler('init', 'al_error');
  function al_error()
  {
    global $page;
    $page['errors'][] = 'Album List folder name is incorrect, uninstall the plugin and rename it to "album_list"';
  }
  return;
}

define('AL_PUBLIC', get_absolute_root_url() . make_index_url(array('section' => 'album_list')) . '/');

add_event_handler('loc_end_section_init', 'al_end_section_init');
add_event_handler('loc_end_index', 'al_end_index', EVENT_HANDLER_PRIORITY_NEUTRAL-10);

/* define page section from url */
function al_end_section_init()
{
  global $tokens, $page, $conf;

  if ('album_list' == $tokens[0])
  {
    $page['section'] = 'album_list';
    $page['title'] = l10n('Album List');
    $page['body_id'] = 'theAlbumListPage';
    $page['is_external'] = true;
    $page['is_homepage'] = false;

    $page['section_title'] = '<a href="'.get_absolute_root_url().'">'.l10n('Home').'</a>'.$conf['level_separator'];
    $page['section_title'].= '<a href="'.AL_PUBLIC.'">'.l10n('Album List').'</a>';
  }
}

function al_end_index()
{
  global $page, $template;

  if ('album_list' != $page['section'])
  {
    return;
  }

  $template->set_filenames(array('album_list' => dirname(__FILE__).'/template/index.tpl'));

  $query = '
SELECT id,name,global_rank,uppercats
  FROM '.CATEGORIES_TABLE.'
'.get_sql_condition_FandF
  (
    array
      (
        'forbidden_categories' => 'id',
        'visible_categories' => 'id'
      ),
    'WHERE'
  ).'
;';
  display_select_cat_wrapper($query, array(), 'album_list', false);

  $template->assign_var_from_handle('PLUGIN_INDEX_CONTENT_BEGIN', 'album_list');
}
