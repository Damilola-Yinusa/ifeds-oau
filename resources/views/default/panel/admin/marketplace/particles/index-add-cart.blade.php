@if (!$item['licensed'] && $item['price'] && $item['is_buy'] && !$item['only_show'])
	@if ($app_is_not_demo)
		@if (in_array($item['slug'], ['chatbot-agent', 'chatbot-voice', 'whatsapp', 'telegram', 'facebook', 'instagram']))
			@if (\App\Helpers\Classes\MarketplaceHelper::isRegistered('chatbot'))
				@if($item['only_premium'])
					<div
						class="inset-0 z-1"
						@if($item['check_subscription'])
							data-toogle="cart"
							data-url="{{ route('dashboard.admin.marketplace.cart.add-delete', $item['id']) }}"
						@else
							onclick="return toastr.info('This extension is for premium customers only.')"
						@endif
					>
						<a href="#">
							<x-tabler-shopping-cart
								class="text-{{ in_array($item['id'], $cartExists) ? 'green' : 'gray' }}-500 h-9 w-9 rounded border p-1"
								id="{{ $item['id'] . '-icon' }}"
							/>
						</a>
					</div>
				@else
					<div
						class="inset-0 z-1"
						data-toogle="cart"
						data-url="{{ route('dashboard.admin.marketplace.cart.add-delete', $item['id']) }}"
					>
						<a href="#">
							<x-tabler-shopping-cart
								class="text-{{ in_array($item['id'], $cartExists) ? 'green' : 'gray' }}-500 h-9 w-9 rounded border p-1"
								id="{{ $item['id'] . '-icon' }}"
							/>
						</a>
					</div>
				@endif


			@else
				<div
					class="inset-0 z-1"
					onclick="return toastr.info('External Chatbot is required for this extension.')"
				>
					<a href="#">
						<x-tabler-shopping-cart
							class="text-{{ in_array($item['id'], $cartExists) ? 'green' : 'gray' }}-500 h-9 w-9 rounded border p-1"
							id="{{ $item['id'] . '-icon' }}"
						/>
					</a>
				</div>
			@endif
		@else
			@if($item['only_premium'])
				<div
					class="inset-0 z-1"
					@if($item['check_subscription'])
						data-toogle="cart"
						data-url="{{ route('dashboard.admin.marketplace.cart.add-delete', $item['id']) }}"
					@else
						onclick="return toastr.info('This extension is for premium customers only.')"
					@endif
				>
					<a href="#">
						<x-tabler-shopping-cart
							class="text-{{ in_array($item['id'], $cartExists) ? 'green' : 'gray' }}-500 h-9 w-9 rounded border p-1"
							id="{{ $item['id'] . '-icon' }}"
						/>
					</a>
				</div>
			@else
				<div
					class="inset-0 z-1"
					data-toogle="cart"
					data-url="{{ route('dashboard.admin.marketplace.cart.add-delete', $item['id']) }}"
				>
					<a href="#">
						<x-tabler-shopping-cart
							class="text-{{ in_array($item['id'], $cartExists) ? 'green' : 'gray' }}-500 h-9 w-9 rounded border p-1"
							id="{{ $item['id'] . '-icon' }}"
						/>
					</a>
				</div>
			@endif
		@endif
	@endif
@endif
