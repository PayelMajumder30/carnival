<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Repositories\PageContentRepository;
use App\Models\{PageContent};

class PageContentController extends Controller
{
    protected $pageContentRepository;

    public function __construct(PageContentRepository $pageContentRepository)
    {
        $this->pageContentRepository = $pageContentRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function PageContentIndex(Request $request)
    {
        $keyword = $request->keyword ?? '';
        $data = $this->pageContentRepository->getAllPageContents($keyword);

        return view('admin.page_content.index', compact('data'));
    }

    public function PageContentCreate()
    {
        return view('admin.page_content.create');
    }

    public function PageContentStore(Request $request)
    {
        $request->validate([
            'page' => 'required|unique:page_contents,page',
            'title' => 'required',
            'description' => 'required',
            // 'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();
        // if ($request->hasFile('image')) {
        //     $file = $request->file('image');
        //     $fileName = time() . rand(10000, 99999) . '.' . $file->getClientOriginalExtension();
        //     $imgPath = public_path('uploads/page_content');
        //     $file->move($imgPath, $fileName);
        //     $data['image'] = "uploads/page_content/" . $fileName;
        // }

        $this->pageContentRepository->storePageContent($data);

        return redirect()->route('admin.page_content.list.all')->with('success', 'Page Content Created Successfully!');
    }

    public function PageContentDetail(Request $request, $id)
    {
        $data = $this->pageContentRepository->getPageContentById($id);
        return view('admin.page_content.detail', compact('data'));
    }

    public function PageContentEdit(Request $request, $id)
    {
        $data = $this->pageContentRepository->getPageContentById($id);
        return view('admin.page_content.edit', compact('data'));
    }

    public function PageContentUpdate(Request $request, $id)
    {
        // dd($request->all());
        $request->validate([
            'page' => 'required|unique:page_contents,page,' . $id,
            'title' => 'required',
            'description' => 'required',
            // 'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();
        // if ($request->hasFile('image')) {
        //     $file = $request->file('image');
        //     $fileName = time() . rand(10000, 99999) . '.' . $file->getClientOriginalExtension();
        //     $imgPath = public_path('uploads/page_content');
        //     $file->move($imgPath, $fileName);
        //     $data['image'] = "uploads/page_content/" . $fileName;
        // }

        $page_content = $this->pageContentRepository->getPageContentById($id);
        $data=$this->pageContentRepository->updatePageContent($page_content, $data);
        // dd($data);
        // dd("yfh");
        return redirect()->route('admin.page_content.list.all')->with('success', 'Page Content Updated Successfully!');
    }
    public function PageContentStatus(Request $request, $id){
        $data = PageContent::find($id);
        $data->status = ($data->status == 1) ? 0 : 1;
        $data->update();
        return response()->json([
            'status'    => 200,
            'message'   => 'Status updated',
        ]);
    }

    public function PageContentDelete($id)
    {
        $page_content = $this->pageContentRepository->getPageContentById($id);
        $this->pageContentRepository->deletePageContent($page_content);

        return redirect()->route('admin.page_content.list.all')->with('success', 'Deleted Successfully');
    }
}
