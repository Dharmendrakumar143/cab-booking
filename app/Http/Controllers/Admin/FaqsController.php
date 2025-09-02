<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Faqs;

class FaqsController extends AdminBaseController
{
    public function index()
    {
        $faqs = Faqs::paginate(10);
        return view('admin.faqs.index',compact('faqs'));
    }

    public function show()
    {
        return view('admin.faqs.add');
    }

    public function edit($faq_id)
    {
        $faq = Faqs::find($faq_id);
        return view('admin.faqs.edit',compact('faq'));
    }

    public function addFaqs(Request $request)
    {
        $request->validate([
            'question' => 'required|string',
            'answer' => 'required|string',
        ]);

        $question = $request->question;
        $answer = $request->answer;
        $question_type = $request->question_type;

        $faq = Faqs::create([
            'question' => $question,
            'answer' => $answer,
            'question_type'=>$question_type
        ]);

        // Flash success message
        $request->session()->flash('success', 'Faq created successfully');

        // Redirect to the rides page
        return redirect()->route('faq-content');

    }

    public function updateFaqs(Request $request)
    {
        $request->validate([
            'question' => 'required|string',
            'answer' => 'required|string',
        ]);

        $faq_id = $request->faq_id;
        $question = $request->question;
        $answer = $request->answer;
        $question_type = $request->question_type;

        $faq = Faqs::find($faq_id);


        $faq->update([
            'question' => $question,
            'answer' => $answer,
            'question_type'=>$question_type
        ]);

        // Flash success message
        $request->session()->flash('success', 'Faq updated successfully');

        // Redirect to the rides page
        return redirect()->route('faq-content');
    }

    public function deleteFaq(Request $request)
    {
        $faq_id = $request->faq_id;
        $faq = Faqs::find($faq_id);
        $faq->delete();

        // Flash success message
        $request->session()->flash('success', 'Faq deleted successfully');

        // Redirect to the rides page
        return redirect()->route('faq-content');
    }


    
}
