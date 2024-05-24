<x-mail::message>
# Hello {{ $task->user->name }},

Task "{{ $task->title }}" is still in progress, kindly finish the task and mark as "done" asap.

Thanks,<br>
{{ config('app.name') }}.
</x-mail::message>
