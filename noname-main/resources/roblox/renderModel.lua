local t = game:GetService("ThumbnailGenerator")
game:GetService("ContentProvider"):SetBaseUrl("http://noname.xyz/")

pcall(function() game.Workspace.Camera:Remove() end) -- This is a hack to make thumbnailcamera work

game:GetService('ScriptContext').ScriptsDisabled = true

 
function tryorelse(tryfunc, failfunc)
    local r
    if(pcall(function () r = tryfunc() end)) then
        return r
    else
        return failfunc()
    end
end

for _,i in ipairs(game:GetObjects("http://noname.xyz/asset/?id={{assetid}}")) do
 	if i.className=='Sky' then
	 print("[THUMBNAIL] Render Sky")
        return tryorelse(
            --function() return t:ClickTexture(i.SkyboxFt, "PNG", 150, 150) end)
            function() return t:Click("PNG", 150, 150, false) end)
 	elseif i.className=='SpecialMesh' then
		print("[THUMBNAIL] Render Mesh")
 		part = Instance:new('Part')
 		part.Parent = workspace
 		i.Parent = part
		return t:Click("PNG", 150, 150, true)
 	else
	 	print("[THUMBNAIL] Render Model")
 		i.Parent = workspace
		return t:Click("PNG", 150, 150, true)
 	end
 end