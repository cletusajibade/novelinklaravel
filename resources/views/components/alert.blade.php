@props(['type' => 'success', 'message'])
@if ($type == 'success')
    <div style="background-color: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 0.75rem 1rem; border-radius: 0.25rem; position: relative; margin-bottom: 0.5rem;"
        role="alert">
        <span style="display: block;">{{ $message }}</span>
        <button type="button"
            style="position: absolute; top: 0; bottom: 0; right: 0; padding: 0.5rem 1rem; background: none; border: none; cursor: pointer;"
            onclick="this.parentElement.style.display='none';">
            <span style="color: #155724; font-size: 1.25rem;">&times;</span>
        </button>
    </div>
@endif

@if ($type == 'error')
    <div style="background-color: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 0.75rem 1rem; border-radius: 0.25rem; position: relative; margin-bottom: 0.5rem;"
        role="alert">
        <span style="display: block;">{{ $message }}</span>
        <button type="button"
            style="position: absolute; top: 0; bottom: 0; right: 0; padding: 0.5rem 1rem; background: none; border: none; cursor: pointer;"
            onclick="this.parentElement.style.display='none';">
            <span style="color: #721c24; font-size: 1.25rem;">&times;</span>
        </button>
    </div>
@endif
