print('[THUMBNAIL] T-Shirt render');

pcall(function() game:GetService('ContentProvider'):SetBaseUrl(url) end)
game:GetService('ThumbnailGenerator').GraphicsMode = 4
game:GetService('ScriptContext').ScriptsDisabled = true
local shirt = game:GetObjects("http://noname.xyz/asset/?id={{assetid}}")[1]
return game:GetService('ThumbnailGenerator'):ClickTexture(shirt.Graphic, "PNG", 150, 150, true)