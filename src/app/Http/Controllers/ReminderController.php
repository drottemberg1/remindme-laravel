<?php

namespace App\Http\Controllers;

use App\Models\Reminder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;

class ReminderController extends Controller
{
  public function index(Request $request)
{
  $limit = $request->query('limit', 10);

  $reminders = $request->user()
      ->reminders()
      ->orderBy('remind_at', 'asc')
      ->limit($limit)
      ->get();

  return response()->json([
      'ok' => true,
      'data' => [
          'reminders' => $reminders,
          'limit' => (int) $limit,
      ]
  ]);
}

public function store(Request $request)
{
  $validated = $request->validate([
      'title' => 'required|string|max:255',
      'description' => 'nullable|string',
      'remind_at' => 'required|date',
      'event_at' => 'required|date',
      'type' => 'sometimes|required|string'
  ]);

  if(strtotime($validated['event_at']) < strtotime($validated['remind_at'])){
    throw new \Exception("reminder cannot be after the event", 1);
  }

  if(strtotime($validated['remind_at']) < time()){
    throw new \Exception("reminder cannot be in the past", 1);
  }

  if(strtotime($validated['event_at']) < time()){
    throw new \Exception("event cannot be in the past", 1);
  }

  $reminder = $request->user()->reminders()->create([
      'status' => 1,
      'created_at'=>date("Y-m-d H:i:00",time()),
      'title' => $validated['title'],
      'description' => $validated['description'],
      'remind_at' => date("Y-m-d H:i:00",strtotime($validated['remind_at'])),
      'event_at' => date("Y-m-d H:i:00",strtotime($validated['event_at'])),
      'type' => isset($validated['type']) && array_search($validated['type'],Reminder::$type, true) !== false ? array_search($validated['type'],Reminder::$type, true) : 0,
    ]);

  return response()->json([
      'ok' => true,
      'data' => $reminder
  ]);
}

public function show(Request $request,$id)
{
$reminder = $request->user()->reminders()->findOrFail($id);

return response()->json([
    'ok' => true,
    'data' => $reminder
]);
}

public function update(Request $request, $id)
{
$reminder = $request->user()->reminders()->findOrFail($id);

$validated = $request->validate([
    'title' => 'string|max:255',
    'description' => 'string',
    'remind_at' => 'date',
    'event_at' => 'date',
    'type' => 'string'
]);



if(isset($validated['event_at'])){
  if(strtotime($validated['event_at']) < time()){
    throw new \Exception("event cannot be in the past", 1);
  }
}
if(isset($validated['event_at'])){
  if(strtotime($validated['remind_at']) < time()){
    throw new \Exception("reminder cannot be in the past", 1);
  }
}
if(isset($validated['event_at']) && isset($validated['remind_at'])){
  if(strtotime($validated['event_at']) < strtotime($validated['remind_at'])){
    throw new \Exception("reminder cannot be after the event", 1);
  }
}


$arrayName = ['status' => 1,'updated_at'=>date("Y-m-d H:i:00",time())];
if(isset($validated['title'] )){
  $arrayName['title'] = $validated['title'];
}
if(isset($validated['description'] )){
  $arrayName['description'] = $validated['description'];
}
if(isset($validated['remind_at'] )){
  $arrayName['remind_at'] = date("Y-m-d H:i:00",strtotime($validated['remind_at']));
}
if(isset($validated['event_at'] )){
  $arrayName['event_at'] = date("Y-m-d H:i:00",strtotime($validated['event_at']));
}
if(isset($validated['type'] )){
    $arrayName['type'] = array_search($validated['type'],Reminder::$type, true) !== false ? array_search($validated['type'],Reminder::$type, true) : 0;
}


//throw new \Exception(date("Y-m-d H:i:00",strtotime($validated['remind_at'])), 1);


$reminder->update(array_filter($arrayName));

return response()->json([
    'ok' => true,
    'data' => $reminder
]);
}


public function destroy(Request $request,$id)
{
$reminder = $request->user()->reminders()->findOrFail($id);
$reminder->delete();

return response()->json(['ok' => true]);
}

}
