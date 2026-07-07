{{-- Step indicator partial — accepts $current (1|2|3) --}}
@php
    $steps = [
        ['label' => 'Email', 'n' => 1],
        ['label' => 'Verify',  'n' => 2],
        ['label' => 'New Password', 'n' => 3],
    ];
@endphp

<div class="steps" role="list" aria-label="Password reset steps">
    @foreach($steps as $i => $step)
        @php
            $state = $step['n'] < $current ? 'done' : ($step['n'] === $current ? 'active' : '');
        @endphp
        
        <div class="step-item {{ $state }}" role="listitem">
            <div class="step-circle" aria-current="{{ $state === 'active' ? 'step' : 'false' }}">
                @if($state === 'done')
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                @else
                    {{ $step['n'] }}
                @endif
            </div>
            <span class="step-label">{{ $step['label'] }}</span>
        </div>

        @if($i < count($steps) - 1)
            <div class="step-connector-wrapper">
                <div class="step-connector {{ $step['n'] < $current ? 'done' : '' }}"></div>
            </div>
        @endif
    @endforeach
</div>
