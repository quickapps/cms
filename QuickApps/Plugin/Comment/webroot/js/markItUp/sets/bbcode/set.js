// ----------------------------------------------------------------------------
// markItUp!
// ----------------------------------------------------------------------------
// Copyright (C) 2008 Jay Salvat
// http://markitup.jaysalvat.com/
// ----------------------------------------------------------------------------
// BBCode tags example
// http://en.wikipedia.org/wiki/Bbcode
// ----------------------------------------------------------------------------
// Feel free to add more tags
// ----------------------------------------------------------------------------
MerkeItUpBbcodeSettings = {
	previewParserPath:	'', // path to your BBCode parser
	markupSet: [
		{name:QuickApps.__t('Bold'), key:'B', openWith:'[b]', closeWith:'[/b]'},
		{name:QuickApps.__t('Italic'), key:'I', openWith:'[i]', closeWith:'[/i]'},
		{name:QuickApps.__t('Underline'), key:'U', openWith:'[u]', closeWith:'[/u]'},
		{separator:'---------------' },
		{name:QuickApps.__t('Video'), key:'Y', replaceWith:'[video][![Youtube/Google URL]!][/video]'},
		{name:QuickApps.__t('Picture'), key:'P', replaceWith:'[img][![URL]!][/img]'},
		{name:QuickApps.__t('Link'), key:'L', openWith:'[url=[![Url]!]]', closeWith:'[/url]', placeHolder: QuickApps.__t('Your text to link here...')},
		{separator:'---------------' },
		{name:QuickApps.__t('Size'), key:'S', openWith:'[size=[![' + QuickApps.__t('Text size') + ']!]]', closeWith:'[/size]',
		dropMenu :[
			{name:QuickApps.__t('Big'), openWith:'[size=200]', closeWith:'[/size]' },
			{name:QuickApps.__t('Normal'), openWith:'[size=100]', closeWith:'[/size]' },
			{name:QuickApps.__t('Small'), openWith:'[size=50]', closeWith:'[/size]' }
		]},
		//{separator:'---------------' },
		//{name:QuickApps.__t('Bulleted list'), openWith:'[list]\n', closeWith:'\n[/list]'},
		//{name:QuickApps.__t('Numeric list'), openWith:'[list=[![' + QuickApps.__t('Starting number') + ']!]]\n', closeWith:'\n[/list]'}, 
		//{name:QuickApps.__t('List item'), openWith:'[*] '},
		{separator:'---------------' },
		{name:QuickApps.__t('Quotes'), openWith:'[quote]', closeWith:'[/quote]'},
		{name:QuickApps.__t('Code'), openWith:'[code]', closeWith:'[/code]'}, 
		{separator:'---------------' },
		{name:QuickApps.__t('Clean'), className:"clean", replaceWith:function(markitup) { return markitup.selection.replace(/\[(.*?)\]/g, "") } },
		//{name:QuickApps.__t('Preview'), className:"preview", call:'preview' }
	]
}

function quoteComment(id){
	var username = $('#comment-'+id+' a.username').html();
	var comment = $('#raw-comment-'+id).html();
	$('textarea#CommentBody').val( $('textarea#CommentBody').val() + '[quote username=' + jQuery.trim(username) + ']' + jQuery.trim(comment) + '[/quote]' );
	$.scrollTo('#CommentBody');
	return true;
}