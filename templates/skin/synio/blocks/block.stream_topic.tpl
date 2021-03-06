<ul class="latest-list">
	{foreach from=$oTopics item=oTopic name="cmt"}
		{assign var="oUser" value=$oTopic->getUser()}							
		{assign var="oBlog" value=$oTopic->getBlog()}
		
		<li class="js-title-comment" title="{$oTopic->getText()|strip_tags|truncate:150:'...'}">
			<p>
				<a href="{$oUser->getUserWebPath()}" class="author">{$oUser->getLogin()}</a>
				<time>{date_format date=$oTopic->getDateAdd() hours_back="12" minutes_back="60" now="60" day="day H:i" format="H:i"}</time>
			</p>
			<a href="{$oTopic->getUrl()}" class="stream-topic">{$oTopic->getTitle()|escape:'html'}</a>
			<span class="block-item-comments"><i class="icon-synio-comments-small"></i>{$oTopic->getCountComment()}</span>
		</li>
	{/foreach}
</ul>


<footer>
	<a href="{router page='index'}new/">{$aLang.block_stream_topics_all}</a> · <a href="{router page='rss'}new/">RSS</a>
</footer>
					