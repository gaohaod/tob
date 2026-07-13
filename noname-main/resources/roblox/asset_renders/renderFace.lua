print('[THUMBNAIL] Face render');

pcall(function() game:GetService('ContentProvider'):SetBaseUrl(url) end)
game:GetService('ThumbnailGenerator').GraphicsMode = 4
game:GetService('ScriptContext').ScriptsDisabled = true
local face = game:GetObjects("http://noname.xyz/asset/?id={{assetid}}")[1]
return game:GetService('ThumbnailGenerator'):ClickTexture(face.Texture, "PNG", 150, 150, true)