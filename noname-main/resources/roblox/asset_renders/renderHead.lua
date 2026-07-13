print('[THUMBNAIL] Render Head')

pcall(function() game:GetService('ContentProvider'):SetBaseUrl(url) end)
game:GetService('ThumbnailGenerator').GraphicsMode = 4
game:GetService('ScriptContext').ScriptsDisabled = true
local guy = game:GetObjects("http://www.noname.xyz/asset/?id=56")[1]
guy.Parent = workspace

local head = game:GetObjects("http://www.noname.xyz/asset/?id={{headid}}")[1]
head.Parent = guy.Head

guy.Parent = workspace
guy.Head.Parent = workspace
guy:remove()

return game:GetService('ThumbnailGenerator'):Click("PNG", 150, 150, true)