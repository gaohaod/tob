print("[THUMBNAIL] Render SpecialMesh")
local ThumbnailGenerator = game:GetService("ThumbnailGenerator")

pcall(function() game:GetService("ContentProvider"):SetBaseUrl("http://www.noname.xyz/") end)
game:GetService("ScriptContext").ScriptsDisabled = true

local prt = Instance.new('Part')
prt.Parent = workspace
local sm = Instance.new('SpecialMesh')
sm.MeshId = "http://noname.xyz/asset/?id={Id}"
sm.Parent = prt

return ThumbnailGenerator:Click("PNG", 220, 220, true, true)