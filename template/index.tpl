{html_style}
.albumList {
	margin:30px;
}
{/html_style}

<div class="albumList">
{foreach from=$album_list item=album}
  <div class="albumListItem">{$album}</div>
{/foreach}
</div>