@props([
    'title',
    'description',
])

<div class="flex w-full flex-col text-center">
    <flux:heading size="xl"><span class="">{{ $title }}</span></flux:heading>
    <flux:subheading>{{ $description }}</flux:subheading>
</div>
