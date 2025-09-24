@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Gantt Chart Overview</h2>
    <ul class="nav nav-tabs" id="ganttTabs" role="tablist">
        @foreach ($tabs as $index => $tab)
            <li class="nav-item" role="presentation">
                <a class="nav-link @if ($index === 0) active @endif" id="tab-{{ $index }}" data-bs-toggle="tab" href="#tabContent-{{ $index }}" role="tab" aria-controls="tabContent-{{ $index }}" aria-selected="true">{{ $tab }}</a>
            </li>
        @endforeach
    </ul>
    <div class="tab-content" id="ganttTabContent">
        @foreach ($tabs as $index => $tab)
            <div class="tab-pane fade @if ($index === 0) show active @endif" id="tabContent-{{ $index }}" role="tabpanel" aria-labelledby="tab-{{ $index }}">
                <!-- Load the Gantt Chart dynamically for each application -->
                @include('gantt.show', ['applicationName' => $tab])
            </div>
        @endforeach
    </div>
</div>
@endsection
