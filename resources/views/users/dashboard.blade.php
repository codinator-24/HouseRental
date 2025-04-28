<x-layout>
    <h1 class="title">Hello {{ explode(' ', auth()->user()->fullName)[0] }}</h1>
</x-layout>
