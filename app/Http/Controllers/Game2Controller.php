<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class Game2Controller extends Controller
{   
    public function listUsers()
    {
        $waitingUsers = Cache::get('waiting_users', []);
        return response()->json(['Users' => $waitingUsers], 200);
    }

    public function enterGame(Request $request)
    {
        $name = $request->input('name');
        $waitingUsers = Cache::get('waiting_users', []);
        $turnCount = Cache::get('turn_count', 1);
    
        if (count($waitingUsers) < 3) {
            $turn = $turnCount;
            $waitingUsers[] = ['name' => $name, 'turn' => $turn];
            $turnCount++;
            Cache::put('waiting_users', $waitingUsers, now()->addMinutes(30));
            Cache::put('turn_count', $turnCount, now()->addMinutes(30));
            $user = ['name' => $name, 'turn' => $turn];
            return response()->json(['message' => 'User added to waiting list', 'Data'=> $user], 200);
        } else {
            return response()->json(['message' => 'Waiting list is full' ], 200);
        }
    }

    public function generateRandomWord()
    {
        $words = ['apple', 'banana', 'orange', 'grape', 'kiwi', 'melon', 'peach', 'pear', 'plum', 'strawberry'];
        $randomWord = $words[array_rand($words)];
        Cache::put('random_word', $randomWord, now()->addMinutes(30));
        return response()->json(['message' => 'Random word generated', 'Word'=> $randomWord], 200);
    }

    public function clearCache()
    {
        Cache::forget('waiting_users');
        Cache::forget('turn_count');

        return response()->json(['message' => 'Cache cleared'], 200);
    }

    public function startGame(Request $request)
    {
        $waitingUsers = Cache::get('waiting_users', []);
        $word = Cache::get('random_word', '');
    
        if (count($waitingUsers) == 3 || $word != '') {
            $currentTurn = Cache::get('current_turn', 1);

            if ($currentTurn > count($waitingUsers)) {
                $currentTurn = 1;
            }
    
            $name = $request->input('name');
    
            if ($this->isPlayerTurn($waitingUsers, $currentTurn, $name)) {
                $playerProgress = Cache::get("player_progress_$name", []);
    
                $isCorrect = $this->checkIfLetterIsCorrect($word, $request->input('letter'));
                $playerProgress = $this->updatePlayerProgress($playerProgress, $request->input('letter'), $isCorrect, $name);
                $currentTurn++;
                Cache::put('current_turn', $currentTurn, now()->addMinutes(30));
    
                if ($isCorrect) {
                    $message = "Letra jugador $name, turno " . ($currentTurn - 1) . " es " . strtoupper($request->input('letter'));
                    $message .= ", Acertó";
                    $message .= ", Progreso " . implode('', $playerProgress);
                    $message .= ", Palabra " . $word;

                    // Verificar si la palabra ha sido completamente adivinada
                    if (!in_array('*', $playerProgress)) {
                        $this->resetGame(); 
                        return response()->json(['message' => $message, 'game_status' => 'Juego terminado, palabra adivinada'], 200);
                    }

                    return response()->json(['message' => $message], 200);
                } else {
                    // Respuesta para letra incorrecta
                    return response()->json(['message' => "Letra jugador $name, turno " . ($currentTurn - 1) . " es " . strtoupper($request->input('letter')) . ", Falló"], 200);
                }
            } else {
                return response()->json(['error' => 'No es tu turno o el nombre es incorrecto. Letra rechazada o alguien adivino la palabra.'], 400);
            }
        } else {
            return response()->json(['message' => 'No se puede iniciar el juego. Jugadores insuficientes.'], 200);
        }
    }
    
    
    private function updatePlayerProgress($playerProgress, $letter, $isCorrect, $playerName)
    {
        if ($isCorrect) {
            $word = Cache::get('random_word', '');
            $updatedProgress = [];

            foreach (str_split($word) as $index => $char) {
                if (strtoupper($char) === strtoupper($letter) || (isset($playerProgress[$index]) && $playerProgress[$index] !== '*')) {
                    $updatedProgress[] = strtoupper($char);
                } else {
                    $updatedProgress[] = '*';
                }
            }

            foreach ($updatedProgress as $index => $char) {
                $playerProgress[$index] = $char;
            }

            Cache::put("player_progress_$playerName", $playerProgress, now()->addMinutes(30));
        }
    
        return $playerProgress;
    }
    
    private function isPlayerTurn($waitingUsers, $currentTurn, $name)
    {
        foreach ($waitingUsers as $user) {
            if ($user['name'] === $name && $user['turn'] === $currentTurn) {
                return true;
            }
        }
    
        return false;
    }
    
    
    private function checkIfLetterIsCorrect($word, $letter)
    {
        return stripos($word, $letter) !== false;
    }
    public function resetGame()
{
    Cache::forget('waiting_users');
    Cache::forget('turn_count');
    Cache::forget('current_turn');
    Cache::forget('random_word');
    return response()->json(['message' => 'Juego reiniciado.'], 200);
}
}
