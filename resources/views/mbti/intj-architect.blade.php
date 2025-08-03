@extends('layouts.app')

@section('content')

<div class="mbti-page">
    <!-- Hero Section -->
    <section class="hero-section">
        <h1>Architect (INTJ)</h1>
        <p>Imaginative and strategic thinkers, with a plan for everything.</p>
    </section>


    <!-- Main Content Layout -->
    <div class="mbti-content-layout layout-container">

        <!-- Sidebar Kiri -->
        <aside class="sidebar-left">
            <ul class="space-y-2 text-sm font-medium">
                <li><a href="#introduction">Introduction</a></li>
                <li><a href="#strengths">Strengths & Weaknesses</a></li>
                <li><a href="#relationships">Romantic Relationships</a></li>
                <li><a href="#friendship">Friendship</a></li>
                <li><a href="#parenthood">Parenthood</a></li>
                <li><a href="#career">Career Paths</a></li>
                <li><a href="#workplace">Workplace Habits</a></li>
                <li><a href="#conclusion">Conclusion</a></li>
            </ul>
        </aside>

        <!-- Konten Utama -->
        <main class="main-content-mbti">
            <section id="introduction">
                <h2 class="text-2xl font-bold mb-2">Introduction</h2>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed non risus.</p>
            </section>
            <section id="strengths">
                <h2 class="text-2xl font-bold mb-2">Strengths & Weaknesses</h2>
                <p>Curabitur nec lacus elit. Pellentesque convallis nisi ac augue pharetra eu tristique.</p>
            </section>
            <section id="relationships">
                <h2 class="text-2xl font-bold mb-2">Romantic Relationships</h2>
                <p>Mauris dignissim, diam sed pellentesque mattis, tortor neque suscipit magna.</p>
            </section>
            <section id="friendship">
                <h2 class="text-2xl font-bold mb-2">Friendship</h2>
                <p>Etiam sit amet ligula non sapien porttitor ullamcorper at sed arcu.</p>
            </section>
            <section id="parenthood">
                <h2 class="text-2xl font-bold mb-2">Parenthood</h2>
                <p>Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae.</p>
            </section>
            <section id="career">
                <h2 class="text-2xl font-bold mb-2">Career Paths</h2>
                <p>Morbi ac felis nec erat laoreet varius. Nunc vitae nulla et sapien gravida placerat.</p>
            </section>
            <section id="workplace">
                <h2 class="text-2xl font-bold mb-2">Workplace Habits</h2>
                <p>Duis varius, mi eu feugiat tincidunt, massa sem rhoncus nulla.</p>
            </section>
            <section id="conclusion">
                <h2 class="text-2xl font-bold mb-2">Conclusion</h2>
                <p>Sed id interdum justo. Cras dapibus, eros at porta commodo, justo est vulputate enim.</p>
            </section>
        </main>

        <!-- Sidebar Kanan -->
        <aside class="sidebar-right">
            <div class="bg-purple-50 border border-purple-200 p-4 rounded">
                <h3 class="font-semibold text-lg text-purple-700 mb-2">Insight of the Day</h3>
                <p class="text-sm text-gray-700">"Architects are imaginative yet decisive, ambitious yet private, and curious yet focused."</p>
            </div>
        </aside>

    </div>
</div>
@endsection

@push('scripts')
<script>
    // Smooth scrolling to content section
    document.querySelectorAll('.scroll-link').forEach(link => {
        link.addEventListener('click', e => {
            e.preventDefault();
            const target = document.querySelector(link.getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth' });
            }
        });
    });
</script>
@endpush