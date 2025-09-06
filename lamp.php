<?php
// Database connection
$host = "localhost";
$db   = "lamp";   
$user = "root";   
$pass = "";       

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch all lamps
    $lamps = $pdo->query("SELECT * FROM lamps ORDER BY lamp_number")->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("DB connection failed: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INSPIRA 2025 Oil Lamps Collection</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #2c1810 0%, #1a0f08 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            font-family: 'Georgia', serif;
            overflow: hidden;
        }

        .center-message {
            position: fixed;
            top: 30px;
            left: 50%;
            transform: translateX(-50%);
            text-align: center;
            color: #f4d03f;
            font-size: 2.5em;
            text-shadow: 0 0 20px rgba(244, 208, 63, 0.5);
            font-weight: normal;
            z-index: 100;
            pointer-events: none;
        }

        .container {
            position: relative;
            width: 100vw;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .lamps-ring {
            position: relative;
            width: 600px;
            height: 600px;
        }

        .inner-lamps-ring {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 200px;
            height: 200px;
            z-index: 5;
        }

        .lamp-card {
            position: absolute;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
            user-select: none;
            width: 80px;
            height: 80px;
            background: transparent;
            border: none;
            padding: 0;
        }

        .lamp-card::before {
            display: none;
        }

        @keyframes glow {
            from { opacity: 0.3; }
            to { opacity: 0.6; }
        }

        .lamp-card:hover {
            transform: translateY(-5px) scale(1.15);
            z-index: 10;
        }

        .lotus-lamp {
            width: 80px;
            height: 80px;
            position: relative;
            z-index: 1;
        }

        .lotus-center {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 20px;
            height: 20px;
            border-radius: 50%;
            z-index: 10;
            transition: all 0.3s ease;
        }

        .lotus-petals {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 80px;
            height: 80px;
        }

        .petal {
            position: absolute;
            width: 18px;
            height: 28px;
            clip-path: ellipse(50% 80% at 50% 20%);
            transform-origin: 50% 40px;
            left: 50%;
            top: 50%;
            margin-left: -9px;
            margin-top: -40px;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 50% 50% 50% 50% / 80% 80% 20% 20%;
        }

        .flame-container {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 20;
            opacity: 0;
            transition: opacity 0.5s ease;
        }

        .flame {
            width: 9px;
            height: 14px;
            background: radial-gradient(ellipse at bottom, #fff 0%, #ffd700 20%, #ff8c00 60%, #ff4500 80%, transparent 100%);
            border-radius: 50% 50% 50% 50% / 60% 60% 40% 40%;
            filter: blur(1px);
            animation: flicker 2s ease-in-out infinite alternate;
            box-shadow: 0 0 12px #ff8c00, 0 0 25px #ffd700, 0 0 38px #ff4500;
        }

        @keyframes flicker {
            0%, 100% { 
                transform: scale(1) rotate(-3deg); 
                opacity: 0.9;
            }
            25% { 
                transform: scale(1.05) rotate(2deg); 
                opacity: 0.95;
            }
            50% { 
                transform: scale(1.1) rotate(-1deg); 
                opacity: 1;
            }
            75% { 
                transform: scale(0.98) rotate(3deg); 
                opacity: 0.92;
            }
        }

        /* Lit state */
        .lamp-card.lit .flame-container {
            opacity: 1;
        }

        .lamp-card.lit .lotus-center,
        .lamp-card.lit .petal {
            filter: brightness(1.8) saturate(1.5);
            box-shadow: 0 0 15px currentColor, 0 0 30px currentColor;
        }

        /* Color variations for outer ring (20 lamps) */
        .lamp-1 .lotus-center { background: linear-gradient(135deg, #ff1493, #ff69b4); }
        .lamp-1 .petal { background: linear-gradient(135deg, rgba(255, 20, 147, 0.9), rgba(255, 105, 180, 0.7)); color: #ff1493; }

        .lamp-2 .lotus-center { background: linear-gradient(135deg, #8a2be2, #4b0082); }
        .lamp-2 .petal { background: linear-gradient(135deg, rgba(138, 43, 226, 0.9), rgba(75, 0, 130, 0.7)); color: #8a2be2; }

        .lamp-3 .lotus-center { background: linear-gradient(135deg, #00bfff, #0077be); }
        .lamp-3 .petal { background: linear-gradient(135deg, rgba(0, 191, 255, 0.9), rgba(0, 119, 190, 0.7)); color: #00bfff; }

        .lamp-4 .lotus-center { background: linear-gradient(135deg, #32cd32, #228b22); }
        .lamp-4 .petal { background: linear-gradient(135deg, rgba(50, 205, 50, 0.9), rgba(34, 139, 34, 0.7)); color: #32cd32; }

        .lamp-5 .lotus-center { background: linear-gradient(135deg, #ff4500, #dc143c); }
        .lamp-5 .petal { background: linear-gradient(135deg, rgba(255, 69, 0, 0.9), rgba(220, 20, 60, 0.7)); color: #ff4500; }

        .lamp-6 .lotus-center { background: linear-gradient(135deg, #ffd700, #ff8c00); }
        .lamp-6 .petal { background: linear-gradient(135deg, rgba(255, 215, 0, 0.9), rgba(255, 140, 0, 0.7)); color: #ffd700; }

        .lamp-7 .lotus-center { background: linear-gradient(135deg, #ffc0cb, #ff69b4); }
        .lamp-7 .petal { background: linear-gradient(135deg, rgba(255, 192, 203, 0.9), rgba(255, 105, 180, 0.7)); color: #ffc0cb; }

        .lamp-8 .lotus-center { background: linear-gradient(135deg, #ba55d3, #8a2be2); }
        .lamp-8 .petal { background: linear-gradient(135deg, rgba(186, 85, 211, 0.9), rgba(138, 43, 226, 0.7)); color: #ba55d3; }

        .lamp-9 .lotus-center { background: linear-gradient(135deg, #40e0d0, #20b2aa); }
        .lamp-9 .petal { background: linear-gradient(135deg, rgba(64, 224, 208, 0.9), rgba(32, 178, 170, 0.7)); color: #40e0d0; }

        .lamp-10 .lotus-center { background: linear-gradient(135deg, #00ff7f, #2e8b57); }
        .lamp-10 .petal { background: linear-gradient(135deg, rgba(0, 255, 127, 0.9), rgba(46, 139, 87, 0.7)); color: #00ff7f; }

        .lamp-11 .lotus-center { background: linear-gradient(135deg, #ff7f50, #ff6347); }
        .lamp-11 .petal { background: linear-gradient(135deg, rgba(255, 127, 80, 0.9), rgba(255, 99, 71, 0.7)); color: #ff7f50; }

        .lamp-12 .lotus-center { background: linear-gradient(135deg, #ffff00, #ffd700); }
        .lamp-12 .petal { background: linear-gradient(135deg, rgba(255, 255, 0, 0.9), rgba(255, 215, 0, 0.7)); color: #ffff00; }

        .lamp-13 .lotus-center { background: linear-gradient(135deg, #c71585, #ff1493); }
        .lamp-13 .petal { background: linear-gradient(135deg, rgba(199, 21, 133, 0.9), rgba(255, 20, 147, 0.7)); color: #c71585; }

        .lamp-14 .lotus-center { background: linear-gradient(135deg, #1e90ff, #0064c8); }
        .lamp-14 .petal { background: linear-gradient(135deg, rgba(30, 144, 255, 0.9), rgba(0, 100, 200, 0.7)); color: #1e90ff; }

        .lamp-15 .lotus-center { background: linear-gradient(135deg, #9370db, #663399); }
        .lamp-15 .petal { background: linear-gradient(135deg, rgba(147, 112, 219, 0.9), rgba(102, 51, 153, 0.7)); color: #9370db; }

        .lamp-16 .lotus-center { background: linear-gradient(135deg, #9400d3, #800080); }
        .lamp-16 .petal { background: linear-gradient(135deg, rgba(148, 0, 211, 0.9), rgba(128, 0, 128, 0.7)); color: #9400d3; }

        .lamp-17 .lotus-center { background: linear-gradient(135deg, #ffb6c1, #ffa07a); }
        .lamp-17 .petal { background: linear-gradient(135deg, rgba(255, 182, 193, 0.9), rgba(255, 160, 122, 0.7)); color: #ffb6c1; }

        .lamp-18 .lotus-center { background: linear-gradient(135deg, #7cfc00, #7fff00); }
        .lamp-18 .petal { background: linear-gradient(135deg, rgba(124, 252, 0, 0.9), rgba(127, 255, 0, 0.7)); color: #7cfc00; }

        .lamp-19 .lotus-center { background: linear-gradient(135deg, #ff8c00, #ffa500); }
        .lamp-19 .petal { background: linear-gradient(135deg, rgba(255, 140, 0, 0.9), rgba(255, 165, 0, 0.7)); color: #ff8c00; }

        .lamp-20 .lotus-center { background: linear-gradient(135deg, #dc143c, #b22222); }
        .lamp-20 .petal { background: linear-gradient(135deg, rgba(220, 20, 60, 0.9), rgba(178, 34, 34, 0.7)); color: #dc143c; }

        /* Color variations for inner ring (5 lamps) */
        .inner-lamp-1 .lotus-center { background: linear-gradient(135deg, #ff6b35, #f7931e); }
        .inner-lamp-1 .petal { background: linear-gradient(135deg, rgba(255, 107, 53, 0.9), rgba(247, 147, 30, 0.7)); color: #ff6b35; }

        .inner-lamp-2 .lotus-center { background: linear-gradient(135deg, #4ecdc4, #44a08d); }
        .inner-lamp-2 .petal { background: linear-gradient(135deg, rgba(78, 205, 196, 0.9), rgba(68, 160, 141, 0.7)); color: #4ecdc4; }

        .inner-lamp-3 .lotus-center { background: linear-gradient(135deg, #fce38a, #f38ba8); }
        .inner-lamp-3 .petal { background: linear-gradient(135deg, rgba(252, 227, 138, 0.9), rgba(243, 139, 168, 0.7)); color: #fce38a; }

        .inner-lamp-4 .lotus-center { background: linear-gradient(135deg, #a8e6cf, #88d8c0); }
        .inner-lamp-4 .petal { background: linear-gradient(135deg, rgba(168, 230, 207, 0.9), rgba(136, 216, 192, 0.7)); color: #a8e6cf; }

        .inner-lamp-5 .lotus-center { background: linear-gradient(135deg, #ff8a80, #ff7043); }
        .inner-lamp-5 .petal { background: linear-gradient(135deg, rgba(255, 138, 128, 0.9), rgba(255, 112, 67, 0.7)); color: #ff8a80; }

        /* Responsive design */
        @media (max-width: 768px) {
            .lamps-ring {
                width: 400px;
                height: 400px;
            }
            
            .inner-lamps-ring {
                width: 150px;
                height: 150px;
            }
            
            .center-message {
                font-size: 1.8em;
            }

            .lamp-card {
                width: 60px;
                height: 60px;
            }

            .lotus-lamp {
                width: 50px;
                height: 50px;
            }

            .lotus-center {
                width: 12px;
                height: 12px;
            }

            .petal {
                width: 12px;
                height: 18px;
                margin-left: -6px;
                margin-top: -25px;
                transform-origin: 50% 25px;
            }

            .flame {
                width: 6px;
                height: 10px;
            }
        }

        @media (max-width: 480px) {
            .lamps-ring {
                width: 300px;
                height: 300px;
            }
            
            .inner-lamps-ring {
                width: 120px;
                height: 120px;
            }
            
            .center-message {
                font-size: 1.4em;
            }

            .lamp-card {
                width: 50px;
                height: 50px;
            }

            .lotus-lamp {
                width: 35px;
                height: 35px;
            }
        }

        /* Touch feedback */
        .lamp-card:active {
            transform: translateY(-2px) scale(0.95);
        }
    </style>
</head>
<body>
    <div class="center-message">
        ✨ WELCOME TO INSPIRA 2025 ✨
    </div>
    <div class="container">
        <div class="lamps-ring" id="lampsRing">
            <!-- Outer ring lamps will be generated by JavaScript -->
            <div class="inner-lamps-ring" id="innerLampsRing">
                <!-- Inner ring lamps will be generated by JavaScript -->
            </div>
        </div>
    </div>

    <script>
        // Function to create a single lamp
        function createLamp(lampNumber, isInner = false) {
            const lampCard = document.createElement('div');
            const className = isInner ? `lamp-card inner-lamp-${lampNumber}` : `lamp-card lamp-${lampNumber}`;
            lampCard.className = className;
            lampCard.onclick = () => toggleLamp(lampCard);
            
            lampCard.innerHTML = `
                <div class="lotus-lamp">
                    <div class="lotus-center"></div>
                    <div class="lotus-petals">
                        ${Array.from({length: 16}, (_, i) => {
                            const angle = (i * 22.5); // 360/16 = 22.5 degrees
                            return `<div class="petal" style="transform: rotate(${angle}deg);"></div>`;
                        }).join('')}
                    </div>
                    <div class="flame-container">
                        <div class="flame"></div>
                    </div>
                </div>
            `;
            
            return lampCard;
        }

        // Function to toggle lamp state
        function toggleLamp(lampElement) {
            lampElement.classList.toggle('lit');
            
            // Add a subtle click animation
            lampElement.style.transform = lampElement.style.transform.includes('scale') 
                ? lampElement.style.transform.replace(/scale\([^)]*\)/, 'scale(0.95)')
                : lampElement.style.transform + ' scale(0.95)';
            
            setTimeout(() => {
                lampElement.style.transform = lampElement.style.transform.replace(/\s*scale\([^)]*\)/, '');
            }, 150);
        }

        // Function to position lamps in a circle with gaps
        function positionLampInCircle(lamp, index, total, radius) {
            const angle = (index / total) * 2 * Math.PI - Math.PI / 2; // Start from top
            const x = Math.cos(angle) * radius;
            const y = Math.sin(angle) * radius;
            
            lamp.style.left = `calc(50% + ${x}px - 40px)`; // 40px = half of lamp width
            lamp.style.top = `calc(50% + ${y}px - 40px)`; // 40px = half of lamp height
        }

        // Generate outer ring (20 lamps)
        function generateOuterLamps() {
            const lampsRing = document.getElementById('lampsRing');
            const radius = window.innerWidth <= 480 ? 140 : window.innerWidth <= 768 ? 180 : 280;
            
            for (let i = 1; i <= 20; i++) {
                const lamp = createLamp(i, false);
                lampsRing.appendChild(lamp);
                positionLampInCircle(lamp, i - 1, 20, radius);
            }
        }

        // Generate inner ring (5 lamps)
        function generateInnerLamps() {
            const innerLampsRing = document.getElementById('innerLampsRing');
            const radius = window.innerWidth <= 480 ? 50 : window.innerWidth <= 768 ? 65 : 85;
            
            for (let i = 1; i <= 5; i++) {
                const lamp = createLamp(i, true);
                innerLampsRing.appendChild(lamp);
                positionLampInCircle(lamp, i - 1, 5, radius);
            }
        }

        // Reposition lamps on window resize
        function repositionLamps() {
            // Reposition outer ring lamps
            const outerLamps = document.querySelectorAll('#lampsRing > .lamp-card:not(.inner-lamps-ring .lamp-card)');
            const outerRadius = window.innerWidth <= 480 ? 140 : window.innerWidth <= 768 ? 180 : 280;
            
            outerLamps.forEach((lamp, index) => {
                positionLampInCircle(lamp, index, 20, outerRadius);
            });

            // Reposition inner ring lamps
            const innerLamps = document.querySelectorAll('#innerLampsRing .lamp-card');
            const innerRadius = window.innerWidth <= 480 ? 55 : window.innerWidth <= 768 ? 70 : 90;
            
            innerLamps.forEach((lamp, index) => {
                positionLampInCircle(lamp, index, 5, innerRadius);
            });
        }

        // Initialize the lamps when the page loads
        document.addEventListener('DOMContentLoaded', function() {
            generateOuterLamps();
            generateInnerLamps();
        });

        // Reposition on window resize
        window.addEventListener('resize', repositionLamps);

        // Add touch support for mobile devices
        document.addEventListener('touchstart', function(e) {
            const lampCard = e.target.closest('.lamp-card');
            if (lampCard) {
                e.preventDefault(); // Prevent default touch behavior
                toggleLamp(lampCard);
            }
        });
    </script>
</body>
</html>
