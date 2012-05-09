// ----------------------------------------------------------------------------
// markItUp!
// ----------------------------------------------------------------------------
// Copyright (C) 2008 Jay Salvat
// http://markitup.jaysalvat.com/
// ----------------------------------------------------------------------------
MerkeItUpBbcodeSettings = {
  nameSpace:		  "bbcode", // Useful to prevent multi-instances CSS conflict
  previewParserPath:  "~/sets/bbcode/preview.php",
  markupSet: [
	  {name:QuickApps.__t('Bold'), key:'B', openWith:'[b]', closeWith:'[/b]'}, 
	  {name:QuickApps.__t('Italic'), key:'I', openWith:'[i]', closeWith:'[/i]'}, 
	  {name:QuickApps.__t('Underline'), key:'U', openWith:'[u]', closeWith:'[/u]'}, 
	  {separator:'---------------' },
	  {name:QuickApps.__t('Picture'), key:'P', replaceWith:'[img][![Url]!][/img]'}, 
	  {name:QuickApps.__t('Link'), key:'L', openWith:'[url=[![Url]!]]', closeWith:'[/url]', placeHolder:'Your text to link here...'},
	  {separator:'---------------' },
	  {name:QuickApps.__t('Colors'), openWith:'[color=[![Color]!]]', closeWith:'[/color]', dropMenu: [
		  {name:QuickApps.__t('Yellow'), openWith:'[color=yellow]', closeWith:'[/color]', className:"col1-1" },
		  {name:QuickApps.__t('Orange'), openWith:'[color=orange]', closeWith:'[/color]', className:"col1-2" },
		  {name:QuickApps.__t('Red'), openWith:'[color=red]', closeWith:'[/color]', className:"col1-3" },
		  {name:QuickApps.__t('Blue'), openWith:'[color=blue]', closeWith:'[/color]', className:"col2-1" },
		  {name:QuickApps.__t('Purple'), openWith:'[color=purple]', closeWith:'[/color]', className:"col2-2" },
		  {name:QuickApps.__t('Green'), openWith:'[color=green]', closeWith:'[/color]', className:"col2-3" },
		  {name:QuickApps.__t('White'), openWith:'[color=white]', closeWith:'[/color]', className:"col3-1" },
		  {name:QuickApps.__t('Gray'), openWith:'[color=gray]', closeWith:'[/color]', className:"col3-2" },
		  {name:QuickApps.__t('Black'), openWith:'[color=black]', closeWith:'[/color]', className:"col3-3" }
	  ]},
	  {name:QuickApps.__t('Size'), key:'S', openWith:'[size=[![Text size]!]]', closeWith:'[/size]', dropMenu :[
		  {name:QuickApps.__t('Big'), openWith:'[size=200]', closeWith:'[/size]' },
		  {name:QuickApps.__t('Normal'), openWith:'[size=100]', closeWith:'[/size]' },
		  {name:QuickApps.__t('Small'), openWith:'[size=50]', closeWith:'[/size]' }
	  ]},
	  {separator:'---------------' },
	  {name:QuickApps.__t('Bulleted list'), openWith:'[list]\n', closeWith:'\n[/list]'}, 
	  {name:QuickApps.__t('Numeric list'), openWith:'[list=[![Starting number]!]]\n', closeWith:'\n[/list]'}, 
	  {name:QuickApps.__t('List item'), openWith:'[*] '}, 
	  {separator:'---------------' },
	  {name:QuickApps.__t('Quotes'), openWith:'[quote]', closeWith:'[/quote]'}, 
	  {name:QuickApps.__t('Code'), openWith:'[code]', closeWith:'[/code]'}, 
	  {separator:'---------------' },
	  {name:QuickApps.__t('Clean'), className:"clean", replaceWith:function(h) { return h.selection.replace(/\[(.*?)\]/g, "") } },
	  {name:QuickApps.__t('Preview'), className:"preview", call:'preview' }
   ]
}