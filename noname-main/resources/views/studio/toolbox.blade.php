<html>
   <head>
      <title>Toolbox</title>
      <link rel="stylesheet" href="/css/toolbox/main.css">
      <script type="text/javascript" src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.11.1.min.js"></script>
      <script type="text/javascript">window.jQuery || document.write("<script type='text/javascript' src='/js/jquery/jquery-1.11.1.js'><\/script>")</script>
      <script type="text/javascript" src="https://ajax.aspnetcdn.com/ajax/jquery.migrate/jquery-migrate-1.2.1.min.js"></script>
      <script type="text/javascript">window.jQuery || document.write("<script type='text/javascript' src='/js/jquery/jquery-migrate-1.2.1.js'><\/script>")</script>
   </head>
   <body class="Page" style="margin: 0;">
      <input name="__RequestVerificationToken" type="hidden" value="6_eZHOjUPq8Jhw66Ug0so8DxlG33_rZY0TrLaXEc7aMbOKqRbphTsdZWYh_pBl5ud60toqWtjSAZmQHQU93ZLxukFYLaUIRjFnWCQD57CiwhlECKHNRU2ejI5FDEhDWcZ1Ru3g2">
      <div id="NewToolboxContainer" data-isuserauthenticated="false" data-isdecalcreationenabled="true" data-requesturl="http://www.noname.xyz/asset/" data-isrecentlyinsertedassetenabled="true">
         <div id="ToolboxControls">
            <div id="SetTabs">
               <div id="Inventory" class="Tabs Selected">All</div>
               <div id="Models" onclick="document.location = '/IDE/ClientToolbox.aspx?type=model'" class="Tabs">Models</div>
               <div id="Audios" onclick="document.location = '/IDE/ClientToolbox.aspx?type=audio'" class="Tabs">Audios</div>
               <div id="Decals" onclick="document.location = '/IDE/ClientToolbox.aspx?type=decal'" class="Tabs">Decals</div>
            </div>
            
            <div id="Navigation" class="Navigation hidden">
            </div>
            <div id="ToolboxItems">
               @foreach ($items as $item)
               <div class="ToolboxItem WithoutVoting" id="span_setitem_{{ $item->id }}" ondragstart="dragRBX({{ $item->id }}, false)">   <a href="javascript:insertContent({{ $item->id }})" class="itemLink" title="{{ $item->name }}">       <img alt="{{ $item->name }}" id="img_span_setitem_{{ $item->id }}" src="@if ($item->type == "audio") /images/audio.png @elseif ($item->type == "decal") /asset/?id={{ $item->id - 1 }} @else /cdn/{{ $item->id }}?t={{ time() }} @endif" border="0px" style="height:62px;width:60px;" onerror="return Roblox.Controls.Image.OnError(this)">   </a></div>
               @endforeach
            </div>
         </div>
      </div>
      <script type="text/javascript">

function insertContent(n) {
    try {
        ClientToolbox.IsRecentlyInsertedAssetEnabled && ClientToolbox.IsUserAuthenticated && UpdateRecentlyInsertedAsset(n), window.external.Insert("http://noname.xyz/asset/?id=" + n);
    } catch (t) {
        alert(ClientToolbox.Resources.insertError);
    }
}
function dragRBX(n, t) {
    try {
        ClientToolbox.IsRecentlyInsertedAssetEnabled && ClientToolbox.IsUserAuthenticated && (UpdateRecentlyInsertedAsset(n), t && makeAssetVoteable(n)), window.external.StartDrag("http://noname.xyz/asset/?id=" + n);
    } catch (i) {
        alert(ClientToolbox.Resources.dragError);
    }
}

         if (typeof ClientToolbox === "undefined") {
             ClientToolbox = {};
         }
         
         ClientToolbox.Resources = {
             //<sl:translate>
             models: "Models",
             recentModels: "Recent models",
             recentDecals: "Recent decals",
             myModels: "My Models",
             myDecals: "My Decals",
             decals: "Decals",
             mySets: "My Sets",
             mySubscribedSets: "My Subscribed Sets",
             robloxSets: "ROBLOX Sets",
             noSets: "No sets are available",
             setsError: "An error occured while retrieving sets",
             results: "Results",
             loading: "Loading...",
             noResults: "No results found.",
             error: "Error Occurred.",
             errorData: "An error occured while retrieving this data",
             insertError: "Could not insert the requested item",
             dragError: "Sorry Could not drag the requested item",
             noVotesYet: "No votes yet",
             endorsedAsset: "A high-quality item",
             //</sl:translate>
             endorsedIcon: "https://web.archive.org/web/20140822185045/http://images.rbxcdn.com/a98989e47370589a088675aaca5eaab8.png"
         };
      </script>
   </body>
</html>
