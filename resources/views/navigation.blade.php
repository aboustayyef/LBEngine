<div class="navigations">
	<div class="navigation_bar">
		<ul class="navigation">
			<li class="navigation__item @if($order=='hot') navigation__item--active @endif  ">
				<a class="navigation__link" href="/posts/hot" title="Order posts by Hotness">Hot</a>
			</li>
			<li class="navigation__item @if($order=='latest') navigation__item--active @endif  ">
				<a class="navigation__link" href="/posts/latest" title="Order posts by date">Latest</a>
			</li>
			<li class="navigation__item @if($order=='top') navigation__item--active @endif  ">
				<a class="navigation__link" href="/posts/top" title="Order posts by viral score">Top</a>
			</li>
		</ul>
	</div>
	@if($order=='top')
	<div class="navigation_bar">
		<ul class="navigation">
			<li class="navigation__item @if($scope=='12h') navigation__item--active @endif  ">
				<a class="navigation__link navigation__link--secondary" href="/posts/top/12h" title="Order posts by Hotness">12 hours</a>
			</li>
			<li class="navigation__item @if($scope=='3d') navigation__item--active @endif  ">
				<a class="navigation__link navigation__link--secondary" href="/posts/top/3d" title="Order posts by date">3 days</a>
			</li>
			<li class="navigation__item @if($scope=='1w') navigation__item--active @endif  ">
				<a class="navigation__link navigation__link--secondary" href="/posts/top/1w" title="Order posts by viral score">1 week</a>
			</li>
		</ul>
	</div>
	@endif
</div>