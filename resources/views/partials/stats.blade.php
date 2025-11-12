<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques Hebdomadaires</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #22223b;
            /* Couleur de fond sombre pour correspondre à votre image */
            color: #e0e0e0;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            /* Alignement en haut */
            min-height: 100vh;
        }

        .container {
            width: 100%;
            max-width: 900px;
            background-color: #22223b;
            padding: 30px;
            border-radius: 15px;
            
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .section-title {
            font-size: 2.2em;
            color: #f0f1f0;
            /* Vert clair pour le titre */
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .emoji {
            font-size: 1.2em;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
            /* Espace avant le bouton ou le graphique */
        }

        .stat-box {
            background-color: #33334d;
            padding: 25px;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
            transition: transform 0.2s ease-in-out;
        }

        .stat-box:hover {
            transform: translateY(-5px);
        }

        .stat-label {
            font-size: 0.9em;
            color: #b0b0b0;
            margin-bottom: 8px;
        }

        .stat-value {
            font-size: 2.5em;
            font-weight: bold;
            color: #ffffff;
        }

        .blue-bg {
            background-image: linear-gradient(135deg, #4CAF50 0%, #2196F3 100%);
        }

        /* Bleu vert */
        .orange-bg {
            background-image: linear-gradient(135deg, #FFC107 0%, #FF9800 100%);
        }

        /* Orange */
        .purple-bg {
            background-image: linear-gradient(135deg, #9C27B0 0%, #673AB7 100%);
        }

        /* Violet */
        .action-button {
            display: block;
            width: 100%;
            padding: 15px;
            margin-top: 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1.1em;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .action-button:hover {
            background-color: #0056b3;
        }

        .chart-container {
            background-color: #33334d;
            padding: 25px;
            border-radius: 12px;
            margin-top: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        .chart-title {
            font-size: 1.5em;
            color: #ffffff;
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <div class="container">
        <div class="header">
            <h2 class="section-title">
                <span class="emoji">📊</span> Statistiques hebdomadaires
            </h2>
        </div>

        <div class="stats-grid">
            <div class="stat-box blue-bg">
                <div class="stat-label">Exercices enregistrés</div>
                <div class="stat-value">{{ $stats['weekly_exercises'] ?? 0 }}</div>
            </div>

            <div class="stat-box orange-bg">
                <div class="stat-label">Muscles distincts</div>
                <div class="stat-value">{{ $stats['weekly_muscles'] ?? 0 }}</div>
            </div>

            <div class="stat-box purple-bg">
                <div class="stat-label">Équipements distincts</div>
                <div class="stat-value">{{ $stats['weekly_equipments'] ?? 0 }}</div>
            </div>
        </div>

        <button class="action-button">
            <span class="emoji">🔄</span> Actualiser
        </button>

        <div class="chart-container" style="height: 300px; max-width: 100%;">
            <canvas id="dailyExercisesChart"></canvas>
        </div>
        {{-- <div class="chart-container">
            <h3 class="chart-title">Exercices quotidiens (7 derniers jours)</h3>
            <canvas id="dailyExercisesChart"></canvas>
        </div> --}}
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('dailyExercisesChart').getContext('2d');
            const dailyExercisesChart = new Chart(ctx, {
                type: 'bar', // Type de graphique (barres)
                data: {
                    labels: @json($stats['chart_labels']), // Les jours de la semaine
                    datasets: [{
                        label: 'Nombre d\'exercices',
                        data: @json($stats['chart_data']), // Le nombre d'exercices par jour
                        backgroundColor: [
                            'rgba(75, 192, 192, 0.6)',
                            'rgba(54, 162, 235, 0.6)',
                            'rgba(255, 206, 86, 0.6)',
                            'rgba(153, 102, 255, 0.6)',
                            'rgba(255, 159, 64, 0.6)',
                            'rgba(255, 99, 132, 0.6)',
                            'rgba(100, 200, 150, 0.6)'
                        ],
                        borderColor: [
                            'rgba(75, 192, 192, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)',
                            'rgba(255, 99, 132, 1)',
                            'rgba(100, 200, 150, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false, // Permet de mieux contrôler la taille
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                color: '#e0e0e0', // Couleur des étiquettes de l'axe Y
                                callback: function (value) { // Assure que les valeurs sont des entiers
                                    if (Number.isInteger(value)) {
                                        return value;
                                    }
                                }
                            },
                            grid: {
                                color: 'rgba(200, 200, 200, 0.2)' // Couleur des lignes de grille Y
                            }
                        },
                        x: {
                            ticks: {
                                color: '#e0e0e0' // Couleur des étiquettes de l'axe X
                            },
                            grid: {
                                color: 'rgba(200, 200, 200, 0.2)' // Couleur des lignes de grille X
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false // Ne pas afficher la légende si un seul dataset
                        },
                        title: {
                            display: false,
                            text: 'Exercices quotidiens'
                        }
                    }
                }
            });
        });
    </script>
</body>

</html>