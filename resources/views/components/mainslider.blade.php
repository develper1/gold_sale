@props(['sliders', 'height' => '30vh', 'autoplay' => 'true'])

<section class="section m-b-0">
    <!-- Block Sliders (Layout 4) -->
    <div class="block block-sliders layout-4 auto-height color-white nav-center">
        <div class="slick-sliders" 
             data-autoplay="{{ $autoplay }}" 
             data-dots="false" 
             data-nav="true" 
             data-columns4="1" 
             data-columns3="1" 
             data-columns2="1" 
             data-columns1="1" 
             data-columns1440="1" 
             data-columns="1">
            @forelse($sliders as $slider)
                <div class="item slick-slide">
                    <div class="item-content">
                        <div class="content-image">
                            <img width="1920" height="781" 
                                 src="{{ asset('storage/app/public/' . $slider->image_path) }}" 
                                 alt="{{ $slider->title ?? 'Slider Image' }}"
                                 loading="lazy">
                        </div>
                        <div class="item-info horizontal-center vertical-middle text-center">
                            <div class="content">
                                @if($slider->title)
                                    <div class="subtitle-slider">{{ $slider->title }}</div>
                                @endif
                                
                                @if($slider->subtitle)
                                    <h2 class="title-slider">{{ $slider->subtitle }}</h2>
                                @endif
                                
                                @if($slider->button_name && $slider->button_link)
                                    <a class="button-slider button button-white button-outline thick-border" 
                                       href="{{ $slider->button_link }}">
                                        {{ $slider->button_name }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="item slick-slide">
                    <div class="item-content">
                        <div class="content-image">
                            <div class="placeholder-slider" style="height: {{ $height }}; background: #f0f0f0; display: flex; align-items: center; justify-content: center;">
                                <span>No slides available</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</section>

@once
    @push('styles')
        <style>
            .block-sliders.layout-4 .item-content .content-image img {
                height: {{ $height }};
                object-fit: cover;
                width: 100%;
            }
            .placeholder-slider {
                color: #999;
                font-size: 1.2rem;
            }
        </style>
    @endpush
@endonce