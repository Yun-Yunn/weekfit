@extends('app')

@section('content')
<div class="flex flex-col justify-center items-center h-screen w-full text-white bg-gray-900 overflow-hidden relative">
    {{-- 🕹️ Écran d’attente avec bouton "Lancer la séance" --}}
    <div id="start-screen" class="absolute inset-0 flex flex-col items-center justify-center bg-gray-900 z-50">
        <h1 class="text-4xl font-bold mb-6 text-amber-400">Prêt à commencer ton entraînement ?</h1>
        <button id="start-btn" class="bg-green-600 hover:bg-green-700 text-white text-xl font-semibold px-6 py-3 rounded-lg shadow-lg transition transform hover:scale-105">
            ▶️ Lancer la séance
        </button>
    </div>

    {{-- ⏱️ Écran du compte à rebours --}}
    <div id="countdown" class="absolute inset-0 hidden flex items-center justify-center bg-gray-900 z-40">
        <h1 id="countdown-number" class="text-8xl font-extrabold text-amber-400 animate-pulse">5</h1>
    </div>

    {{-- 💪 Contenu principal des exercices --}}
    <div id="exercise-content" class="opacity-0 transition-opacity duration-700">
        <div class="bg-gray-800 rounded-2xl shadow-2xl p-6 w-[90%] max-w-3xl text-center overflow-hidden">
            <h1 class="text-3xl font-bold mb-6">Entraînement du jour</h1>

            <div id="exercise-container">
                @forelse ($exercises as $index => $exercise)
                    @php $gif = optional($exercise->images->first())->image; @endphp

                    <div class="exercise-card transition-opacity duration-500 {{ $index !== 0 ? 'hidden opacity-0' : 'opacity-100' }}">
                        @if ($gif)
                            <img src="{{ $gif }}" alt="{{ $exercise->translated_name }}"
                                 class="mx-auto rounded-lg mb-4 shadow-md max-h-[320px] object-contain bg-gray-700">
                        @endif

                        <h2 class="text-2xl font-semibold text-amber-400 mb-3">{{ $exercise->translated_name }}</h2>

                        <p class="text-gray-300 mb-4 text-sm leading-relaxed overflow-hidden text-ellipsis">
                            {!! Str::limit(strip_tags($exercise->translated_description), 350, '...') !!}
                        </p>

                        <hr class="border-gray-600 my-4">

                        <p><strong>Muscle principal :</strong> {{ $exercise->translated_muscle }}</p>
                        <p><strong>Équipement :</strong> {{ $exercise->translated_equipment }}</p>

                        <div class="flex justify-center gap-4 mt-4">
                            <button class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded next-btn">Pas fait</button>
                            <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded next-btn">Fait</button>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-400">Aucun exercice disponible</p>
                @endforelse

                <div id="end-message" class="hidden text-2xl font-bold text-green-400 mt-8">
                    Entraînement terminé ! Reviens demain.
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const startScreen = document.getElementById('start-screen');
    const startBtn = document.getElementById('start-btn');
    const countdown = document.getElementById('countdown');
    const countdownNumber = document.getElementById('countdown-number');
    const exerciseContent = document.getElementById('exercise-content');

    let count = 5;

    startBtn.addEventListener('click', () => {
        startScreen.classList.add('hidden');
        countdown.classList.remove('hidden');

        const timer = setInterval(() => {
            if (count > 1) {
                count--;
                countdownNumber.textContent = count;
            } else if (count === 1) {
                countdownNumber.textContent = "GO !";
                count--;
            } else {
                clearInterval(timer);
                countdown.classList.add('hidden');
                exerciseContent.classList.remove('opacity-0');
                exerciseContent.classList.add('opacity-100');
            }
        }, 1000);
    });

    // Gestion des boutons suivant/précédent
    const cards = document.querySelectorAll('.exercise-card');
    const nextButtons = document.querySelectorAll('.next-btn');
    const endMessage = document.getElementById('end-message');
    let current = 0;

    nextButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            cards[current].classList.remove('opacity-100');
            cards[current].classList.add('opacity-0');

            setTimeout(() => {
                cards[current].classList.add('hidden');
                current++;

                if (current < cards.length) {
                    cards[current].classList.remove('hidden');
                    setTimeout(() => cards[current].classList.add('opacity-100'), 100);
                } else {
                    endMessage.classList.remove('hidden');
                }
            }, 400);
        });
    });
});
</script>
@endsection
