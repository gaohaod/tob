pcall(function() game:GetService('ContentProvider'):SetBaseUrl("http://www.noname.xyz/") end)
print("[THUMBNAIL] Render Hat")
game:GetService('ThumbnailGenerator').GraphicsMode = 4
game:GetService('ScriptContext').ScriptsDisabled = true
pcall(function() game.Workspace.Camera:Remove() end) 
game:GetObjects("http://www.noname.xyz/asset/?id={{ hatId }}")[1].Parent = workspace
t = game:GetService('ThumbnailGenerator')
return t:Click("PNG", 220, 220, true, true)