{{--@if(count($pagination)>0)--}}
{{--    <div class="pagination">--}}
{{--        @if( $pagination->lastPage()>1)--}}
{{--            --}}{{--            if page is first--}}
{{--            @if(!$pagination->onFirstPage())--}}
{{--                <a href="{{$pagination->previousPageUrl()}}" class="left_arrow"></a>--}}
{{--            @endif--}}
{{--            --}}{{--        if number of pages is greater than 3 this is used for only showing first page  --}}
{{--            @if($pagination->currentPage()>=3)--}}
{{--                <a href="{{$pagination->getOptions()["path"]."?page=".(1)}}"--}}
{{--                   style=" text-decoration: none!important;"--}}
{{--                   class="page_number {{$pagination->currentPage() ==1 ? "active" :""}}">1</a>--}}
{{--            @endif--}}

{{--            @if($pagination->currentPage()<=2)--}}

{{--                @for($i=0;$i<$pagination->lastPage()-1;$i++)--}}
{{--                    <a href="{{$pagination->getOptions()["path"]."?page=".($i+1)}}"--}}
{{--                       style=" text-decoration: none!important;"--}}
{{--                       class="page_number {{$pagination->currentPage() ==$i+1 ? "active" :""}}">{{$i+1}}</a>--}}
{{--                @endfor--}}
{{--            @elseif($pagination->currentPage()+2>$pagination->lastPage())--}}
{{--                @foreach($pagination->getUrlRange($pagination->lastPage()-2,$pagination->lastPage()) as $index=>$page)--}}
{{--                    <a href="{{$page}}"--}}
{{--                       style=" text-decoration: none!important;"--}}
{{--                       class="page_number {{$pagination->currentPage() ==$index ? "active" :""}}">{{$index}}</a>--}}
{{--                @endforeach--}}
{{--            @else--}}
{{--                @foreach($pagination->getUrlRange($pagination->currentPage()-1,$pagination->currentPage()+1) as $index=>$page)--}}
{{--                    <a href="{{$page}}"--}}
{{--                       style=" text-decoration: none!important;"--}}
{{--                       class="page_number {{$pagination->currentPage() ==$index ? "active" :""}}">{{$index}}</a>--}}
{{--                @endforeach--}}
{{--            @endif--}}

{{--            @if($pagination->currentPage()<$pagination->lastPage()-1)--}}
{{--                <a href="{{$pagination->getOptions()["path"]."?page=".$pagination->lastPage()}}"--}}
{{--                   style=" text-decoration: none!important;"--}}
{{--                   class="page_number {{$pagination->currentPage() ==$pagination->lastPage() ? "active" :""}}">{{$pagination->lastPage()}}</a>--}}
{{--            @endif--}}
{{--            @if($pagination->hasMorePages())--}}
{{--                <a href="{{$pagination->nextPageUrl()}}" class="right_arrow"></a>--}}
{{--            @endif--}}
{{--        @endif--}}
{{--    </div>--}}
{{--@endif--}}
@if(count($pagination)>0)
<div class="pagination">
    <a href="{{$pagination->previousPageUrl()}}" class="left_arrow"></a>
    @foreach( $pagination->links()->elements[0] as $item =>$key)
        <a href="{{$key}}"  style=" text-decoration: none!important;" class="page_number ">{{$item}}</a>
    @endforeach

    <a href="{{$pagination->nextPageUrl()}}" class="right_arrow"></a>
</div>
@endif