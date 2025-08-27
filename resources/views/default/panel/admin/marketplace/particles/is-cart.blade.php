@if((! $item['licensed']) && $item['price'] && $item['is_buy'])
	@if(in_array($item['slug'], ['chatbot-agent', 'chatbot-voice']) || in_array($item['slug'], ['whatsapp', 'telegram', 'facebook', 'instagram']))

		@if(\App\Helpers\Classes\MarketplaceHelper::isRegistered('chatbot'))

			@if(in_array($item['slug'], ['whatsapp', 'telegram', 'facebook', 'instagram']))
				@if(\App\Helpers\Classes\MarketplaceHelper::getDbVersion('chatbot') >= 3)
					@if($item['only_premium'])
						@if($item['check_subscription'])
							<x-button
								data-toogle="cart"
								data-url="{{ route('dashboard.admin.marketplace.cart.add-delete', $item['id']) }}"
								class="relative ms-2"
								variant="ghost-shadow"
								href="#">
								<x-tabler-shopping-cart id="{{ $item['id'].'-icon' }}"
														class="size-7 text-{{ in_array($item['id'], $cartExists) ? 'green' : 'gray' }}-500"/>
							</x-button>
						@else
{{--							<x-button--}}
{{--								class="relative ms-2"--}}
{{--								variant="ghost-shadow"--}}
{{--								href="#"--}}
{{--								onclick="return toastr.info('This extension is for premium customers only.')"--}}
{{--							>--}}
{{--								<x-tabler-shopping-cart id="{{ $item['id'].'-icon' }}"--}}
{{--														class="size-7 text-{{ in_array($item['id'], $cartExists) ? 'green' : 'gray' }}-500"/>--}}
{{--							</x-button>--}}
						@endif
					@else
						<x-button
							data-toogle="cart"
							data-url="{{ route('dashboard.admin.marketplace.cart.add-delete', $item['id']) }}"
							class="relative ms-2"
							variant="ghost-shadow"
							href="#">
							<x-tabler-shopping-cart id="{{ $item['id'].'-icon' }}"
													class="size-7 text-{{ in_array($item['id'], $cartExists) ? 'green' : 'gray' }}-500"/>
						</x-button>
					@endif
				@else
					<x-button
						class="relative ms-2"
						variant="ghost-shadow"
						href="#"
						onclick="return toastr.info('This extension requires an external chatbot for its 3.0 version.')"
					>
						<x-tabler-shopping-cart id="{{ $item['id'].'-icon' }}"
												class="size-7 text-{{ in_array($item['id'], $cartExists) ? 'green' : 'gray' }}-500"/>
					</x-button>
				@endif
			@else
				@if($item['only_premium'])
					@if($item['check_subscription'])
						<x-button
							data-toogle="cart"
							data-url="{{ route('dashboard.admin.marketplace.cart.add-delete', $item['id']) }}"
							class="relative ms-2"
							variant="ghost-shadow"
							href="#">
							<x-tabler-shopping-cart id="{{ $item['id'].'-icon' }}"
													class="size-7 text-{{ in_array($item['id'], $cartExists) ? 'green' : 'gray' }}-500"/>
						</x-button>
					@else
{{--						<x-button--}}
{{--							class="relative ms-2"--}}
{{--							variant="ghost-shadow"--}}
{{--							href="#"--}}
{{--							onclick="return toastr.info('This extension is for premium customers only.')"--}}
{{--						>--}}
{{--							<x-tabler-shopping-cart id="{{ $item['id'].'-icon' }}"--}}
{{--													class="size-7 text-{{ in_array($item['id'], $cartExists) ? 'green' : 'gray' }}-500"/>--}}
{{--						</x-button>--}}
					@endif
				@else
					<x-button
						data-toogle="cart"
						data-url="{{ route('dashboard.admin.marketplace.cart.add-delete', $item['id']) }}"
						class="relative ms-2"
						variant="ghost-shadow"
						href="#">
						<x-tabler-shopping-cart id="{{ $item['id'].'-icon' }}"
												class="size-7 text-{{ in_array($item['id'], $cartExists) ? 'green' : 'gray' }}-500"/>
					</x-button>
				@endif
			@endif
		@else

			<x-button
				onclick="return toastr.info('External Chatbot is required for this extension.')"
				class="relative ms-2"
				variant="ghost-shadow"
				href="#">
				<x-tabler-shopping-cart
					id="{{ $item['id'].'-icon' }}"
					class="size-7 text-{{ in_array($item['id'], $cartExists) ? 'green' : 'gray' }}-500"
				/>
			</x-button>

		@endif
	@else
		@if($item['only_premium'])
			@if($item['check_subscription'])
				<x-button
					data-toogle="cart"
					data-url="{{ route('dashboard.admin.marketplace.cart.add-delete', $item['id']) }}"
					class="relative ms-2"
					variant="ghost-shadow"
					href="#">
					<x-tabler-shopping-cart id="{{ $item['id'].'-icon' }}"
											class="size-7 text-{{ in_array($item['id'], $cartExists) ? 'green' : 'gray' }}-500"/>
				</x-button>
			@else
				{{--						<x-button--}}
				{{--							class="relative ms-2"--}}
				{{--							variant="ghost-shadow"--}}
				{{--							href="#"--}}
				{{--							onclick="return toastr.info('This extension is for premium customers only.')"--}}
				{{--						>--}}
				{{--							<x-tabler-shopping-cart id="{{ $item['id'].'-icon' }}"--}}
				{{--													class="size-7 text-{{ in_array($item['id'], $cartExists) ? 'green' : 'gray' }}-500"/>--}}
				{{--						</x-button>--}}
			@endif
		@else
			<x-button
				data-toogle="cart"
				data-url="{{ route('dashboard.admin.marketplace.cart.add-delete', $item['id']) }}"
				class="relative ms-2"
				variant="ghost-shadow"
				href="#">
				<x-tabler-shopping-cart id="{{ $item['id'].'-icon' }}"
										class="size-7 text-{{ in_array($item['id'], $cartExists) ? 'green' : 'gray' }}-500"/>
			</x-button>
		@endif
	@endif

@endif
