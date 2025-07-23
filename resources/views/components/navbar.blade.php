<nav class="navbar relative bg-white shadow-md z-50">
    <div class="navbar-left flex items-center gap-6 px-6 py-3">
        <a href="{{ url('/HEI-personality-test') }}">
            <img src="{{ asset('img/logohei.png') }}" alt="Logo" class="logo-img h-10">
        </a>

        <div class="dropdown group relative">
            <button class="dropdown-button">
                Personality Types
            </button>

            <!-- Dropdown Menu -->
            <div class="dropdown-menu absolute left-0 top-full w-max bg-white shadow-lg opacity-0 invisible group-hover:visible group-hover:opacity-100 transition-opacity duration-200">
                <div class="dropdown-rows p-4 grid grid-cols-1 gap-6 max-h-[400px] overflow-y-auto">
                    <!-- Analysts -->
                    <div class="dropdown-row">
                        <h3 class="dropdown-header text-purple">Analysts</h3>
                        <p class="dropdown-desc">
                            Intuitive (<b>N</b>) and Thinking (<b>T</b>) personality types, known for their rationality, impartiality, and intellectual excellence.
                        </p>
                        <div class="dropdown-buttons flex flex-wrap gap-2 mt-2">
                            <a href="{{ url('mbti/intj-architect') }}" class="dropdown-btn btn-purple">Architect</a>
                            <a href="#" class="dropdown-btn btn-purple">Logician</a>
                            <a href="#" class="dropdown-btn btn-purple">Commander</a>
                            <a href="#" class="dropdown-btn btn-purple">Debater</a>
                        </div>
                    </div>
                    <!-- Diplomats -->
                    <div class="dropdown-row">
                        <h3 class="dropdown-header text-green">Diplomats</h3>
                        <p class="dropdown-desc">
                            Intuitive (<b>N</b>) and Feeling (<b>F</b>) personality types, known for their empathy, diplomatic skills, and passionate idealism.
                        </p>
                        <div class="dropdown-buttons">
                            <a href="#" class="dropdown-btn btn-green">Advocate</a>
                            <a href="#" class="dropdown-btn btn-green">Mediator</a>
                            <a href="#" class="dropdown-btn btn-green">Protagonist</a>
                            <a href="#" class="dropdown-btn btn-green">Campaigner</a>
                        </div>
                    </div>
                    <!-- Sentinels -->
                    <div class="dropdown-row">
                        <h3 class="dropdown-header text-blue">Sentinels</h3>
                        <p class="dropdown-desc">
                            Observant (<b>S</b>) and Judging (<b>J</b>) personality types, known for their practicality and focus on order, security, and stability.
                        </p>
                        <div class="dropdown-buttons">
                            <a href="#" class="dropdown-btn btn-blue">Logistician</a>
                            <a href="#" class="dropdown-btn btn-blue">Defender</a>
                            <a href="#" class="dropdown-btn btn-blue">Executive</a>
                            <a href="#" class="dropdown-btn btn-blue">Consul</a>
                        </div>
                    </div>
                    <!-- Explorers -->
                    <div class="dropdown-row">
                        <h3 class="dropdown-header text-yellow">Explorers</h3>
                        <p class="dropdown-desc">
                            Observant (<b>S</b>) and Prospecting (<b>P</b>) personality types, known for their spontaneity, ingenuity, and flexibility.
                        </p>
                        <div class="dropdown-buttons">
                            <a href="#" class="dropdown-btn btn-yellow">Virtuoso</a>
                            <a href="#" class="dropdown-btn btn-yellow">Adventurer</a>
                            <a href="#" class="dropdown-btn btn-yellow">Entrepreneur</a>
                            <a href="#" class="dropdown-btn btn-yellow">Entertainer</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <button class="nav-button">About Us</button>
    </div>
</nav>
