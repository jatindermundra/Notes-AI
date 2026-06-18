<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Note;
use Illuminate\Http\Request;
use OpenAI\Laravel\Facades\OpenAI;

class NoteController extends Controller
{
    public function index()
    {
        return Note::paginate(10);
    }

   public function store(Request $request)
{
    $request->validate([
        'title' => 'required|max:255',
        'content' => 'required'
    ]);

    $embeddingResponse = OpenAI::embeddings()->create([
        'model' => 'text-embedding-3-small',
        'input' => $request->content,
    ]);

    $embedding = $embeddingResponse
        ->embeddings[0]
        ->embedding;

    $note = Note::create([
        'title' => $request->title,
        'content' => $request->content,
        'embedding' => $embedding
    ]);

    return response()->json([
        'success' => true,
        'data' => $note
    ], 201);
}

    public function show(Note $note)
    {
        return response()->json($note);
    }

    public function update(Request $request, Note $note)
    {
        $note->update($request->all());

        return response()->json($note);
    }

    public function destroy(Note $note)
    {
        $note->delete();

        return response()->json([], 204);
    }
	private function cosineSimilarity($a, $b)
	{
		$dot = 0;
		$normA = 0;
		$normB = 0;

		foreach ($a as $i => $value) {
			$dot += $value * $b[$i];
			$normA += $value * $value;
			$normB += $b[$i] * $b[$i];
		}

		return $dot /
		(sqrt($normA) * sqrt($normB));
	}
	public function search(Request $request)
	{
		$query = $request->q;

		$embeddingResponse =
			OpenAI::embeddings()->create([
				'model'=>'text-embedding-3-small',
				'input'=>$query
			]);

		$queryEmbedding =
			$embeddingResponse->embeddings[0]->embedding;

		$notes = Note::all();

		foreach ($notes as $note) {

			$note->score =
				$this->cosineSimilarity(
					$queryEmbedding,
					$note->embedding
				);
		}

		return $notes
			->sortByDesc('score')
			->values();
	}
	public function summary($id)
{
    $note = Note::findOrFail($id);

    $response =
        OpenAI::chat()->create([
            'model'=>'gpt-4o-mini',
            'messages'=>[
                [
                    'role'=>'user',
                    'content'=>
                    'Summarize this note: '
                    .$note->content
                ]
            ]
        ]);

    $summary =
        $response->choices[0]
        ->message
        ->content;

    $note->summary = $summary;
    $note->save();

    return response()->json([
        'summary'=>$summary
    ]);
}

}