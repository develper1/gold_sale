{{-- resources/views/components/page-header.blade.php --}}

@props([
    'title' => '',
    'breadcrumbs' => [],
    'bgColor' => '#ffffff',
    'titleSize' => '28px',
    'padding' => '16px 0'
])

<div id="title" class="page-title" style="background-color: {{ $bgColor }}; padding: {{ $padding }};">
    <div class="section-container" style="max-width: 1200px; margin: 0 auto; padding: 0 24px;">
        
        {{-- Page Title - centered --}}
        <div class="content-title-heading" style="text-align: center; margin-bottom: {{ count($breadcrumbs) > 0 ? '12px' : '0' }};">
            <h1 class="text-title-heading" style="margin: 0; font-size: {{ $titleSize }};">
                {{ $title }}
            </h1>
        </div>
        
        {{-- Breadcrumbs - centered below title, wraps naturally --}}
        @if(count($breadcrumbs) > 0)
        <div class="breadcrumbs" style="text-align: center; line-height: 1.6;">
            @foreach($breadcrumbs as $index => $crumb)
                @if($index > 0)
                    <span class="delimiter" style="margin: 0 8px; color: #999;"></span>
                @endif
                
                @if(isset($crumb['url']) && !$loop->last)
                    <a href="{{ $crumb['url'] }}" style="color: #666; text-decoration: none; white-space: nowrap;">{{ $crumb['label'] }}</a>
                @else
                    <span style="color: #333; white-space: nowrap;">{{ $crumb['label'] }}</span>
                @endif
            @endforeach
        </div>
        @endif
        
    </div>
</div>