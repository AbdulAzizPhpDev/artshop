<div class="p2">
    <div class="content">
        <div class="b1" id="catalog_tag_id">
             
            @foreach($catalogs as $key => $catalog)
         
                @if ($loop->first)
                    <a href="{{route('user.products_by_catalog',['catalog_id'=>$catalog->id,'lang'=>app()->getLocale()])}}"
                       class="b2" 
                       style="margin-bottom:20px ; color: #FFFFFF; display: block !important; background:url('{{$catalog->image ? Storage::url($catalog->image): "/1.jpg" }}') 50% 0px no-repeat;">
                        <div class="overlay2"></div>
                        <div class="t2 t2_1">{{$catalog['name_'.app()->getLocale()]}}</div>
                    </a>
                @else
                    <a href="{{route('user.products_by_catalog',['catalog_id'=>$catalog->id,'lang'=>app()->getLocale()])}}"
                       class="b3"
                       style="margin-bottom:20px; color: #FFFFFF; display: block !important; background:url('{{$catalog->image ? Storage::url($catalog->image): "/1.jpg" }}') 50% 0px no-repeat;">
                        <div class="overlay2"></div>
                        <div class="t2 t2_1">{{$catalog['name_'.app()->getLocale()]}}</div>
                    </a>
                @endif

            @endforeach


        </div>
        @if(count($catalogs)>0)
            <div class="btn" id="catalog_button_id" onclick="catalogPrePages()">{{ucfirst(__('user_index_show_more'))}}</div>
        @endif

    </div>

</div>
